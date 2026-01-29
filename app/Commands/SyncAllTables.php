<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class SyncAllTables extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'sync:all';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Sync all MySQL tables to PostgreSQL (data only, truncate + insert).';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    private function syncSequenceForTable($pgsql, string $table)
    {
        try {
            $serialCols = $pgsql->query("
                SELECT a.attname AS column_name
                FROM pg_attribute a
                JOIN pg_class t ON a.attrelid = t.oid
                JOIN pg_namespace n ON t.relnamespace = n.oid
                WHERE t.relname = ?
                  AND a.attnum > 0
                  AND NOT a.attisdropped
                  AND pg_get_serial_sequence(format('%I.%I', n.nspname, t.relname), a.attname) IS NOT NULL
            ", [$table])->getResultArray();

            if (empty($serialCols)) {
                return; // No auto-increment columns
            }

            // protect table name by doubling any quotes (to be safe)
            $safeTable = str_replace('"', '""', $table);
            $qualifiedTable = 'public."' . $safeTable . '"';

            foreach ($serialCols as $sc) {
                $col = $sc['column_name'];

                // Build SQL carefully: column name is injected into the subquery with quotes,
                // table is already properly quoted in $qualifiedTable.
                $sql = "SELECT setval(
                            pg_get_serial_sequence(?, ?),
                            (SELECT COALESCE(MAX(\"{$col}\"), 0) FROM {$qualifiedTable}),
                            true
                        )";

                // Bindings: first param = table identifier for pg_get_serial_sequence, second = column name
                $pgsql->query($sql, [$qualifiedTable, $col]);
            }

            CLI::write("  ↳ Sequence synced for {$table}", 'green');
        } catch (\Throwable $e) {
            CLI::error("Sequence sync failed for {$table}: " . $e->getMessage());
        }
    }


    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        log_message("info", "Starting full DB sync...");
        CLI::write("Starting full sync...", 'yellow');

        $mysql = Database::connect('liveDB');
        $pgsql = Database::connect();

        $mysqlTables = $mysql->listTables();
        CLI::write("All tables: ".json_encode($mysqlTables));
        $conversionSummary = [];

        foreach ($mysqlTables as $table) {
            CLI::write("Syncing table: {$table}", 'cyan');
            $conversionSummary[$table] = [];

            try {
                $quotedMySQLTable = "`{$table}`";
                $query = $mysql->table($quotedMySQLTable)->get();
                if (!$query) throw new \Exception("Failed to fetch rows for table: {$table}");
                $rows = $query->getResultArray();

                if (empty($rows)) {
                    CLI::write("No rows found in {$table}, skipping...", 'yellow');
                    continue;
                }

                $pgColsRaw = $pgsql->query(
                    'SELECT column_name, data_type, is_nullable 
                     FROM information_schema.columns 
                     WHERE table_name = ?',
                    [$table]
                )->getResultArray();

                $columnTypes    = [];
                $notNullColumns = [];
                $checkConstraintColumns = []; // Tracks columns with check constraints

                // NEW: for submit_event_tbl.repeating_event allowed values from check constraint
                $submitRepeatingAllowedValues = null;

                foreach ($pgColsRaw as $col) {
                    $colNameLower = strtolower($col['column_name']);
                    $columnTypes[$colNameLower] = $col['data_type'];
                    if ($col['is_nullable'] === 'NO') $notNullColumns[$colNameLower] = true;

                    // Detect check constraints
                    $constraint = $pgsql->query("
                        SELECT conname
                        FROM pg_constraint
                        WHERE conrelid = ?::regclass
                        AND contype = 'c'
                        AND conkey::text LIKE ?
                    ", [$table, "%{$colNameLower}%"])->getRowArray();

                    if ($constraint) $checkConstraintColumns[$colNameLower] = true;
                }

                // NEW: read the actual check constraint definition for submit_event_tbl.repeating_event
                if ($table === 'submit_event_tbl') {
                    try {
                        $constraintDefs = $pgsql->query("
                                            SELECT pg_get_constraintdef(c.oid) AS def
                                            FROM pg_constraint c
                                            WHERE c.conrelid = ?::regclass
                                            AND c.contype = 'c'
                                        ", [$table])->getResultArray();

                        foreach ($constraintDefs as $cd) {
                            if (!isset($cd['def'])) continue;
                            $def = $cd['def'];

                            if (stripos($def, 'repeating_event') === false) continue;

                            // 1) Try IN(...) pattern first: CHECK (repeating_event IN ('Yes','No'))
                            if (preg_match("/IN\s*\\((.*?)\\)/i", $def, $mIn)) {
                                $items = array_map('trim', explode(',', $mIn[1]));
                                $items = array_map(function ($s) {
                                    return trim($s, " '\"");
                                }, $items);
                                if (!empty($items)) {
                                    $submitRepeatingAllowedValues = $items;
                                    break;
                                }
                            }

                            // 2) Try ARRAY[...] pattern: CHECK ((repeating_event = ANY (ARRAY['yes'::text, 'no'::text])))
                            if (preg_match("/ARRAY\s*\\[(.*?)\\]/i", $def, $mArr)) {
                                $items = array_map('trim', explode(',', $mArr[1]));
                                $items = array_map(function ($s) {
                                    // remove possible type casts like ::text and surrounding quotes
                                    $s = preg_replace("/::[a-z0-9_]+/i", '', $s);
                                    return trim($s, " '\"");
                                }, $items);
                                if (!empty($items)) {
                                    $submitRepeatingAllowedValues = $items;
                                    break;
                                }
                            }

                            // 3) Fallback: try to find single-quoted tokens anywhere as a last resort
                            if (preg_match_all("/'([^']+)'/", $def, $mAll)) {
                                $items = array_map('trim', $mAll[1]);
                                if (!empty($items)) {
                                    $submitRepeatingAllowedValues = $items;
                                    break;
                                }
                            }
                        }
                    } catch (\Throwable $eConstraint) {
                        // Do not break existing flow/logs; just log extra info if it fails
                        log_message(
                            'error',
                            "Failed to read repeating_event constraint for submit_event_tbl: " . $eConstraint->getMessage()
                        );
                    }
                }

                $sanitizeDate = function ($value, $isNotNull, $type) use ($table, &$conversionSummary, $colNameLower) {
                    if (!is_string($value)) return $value;
                    if (
                        preg_match('/^(0000-00-00)( 00:00:00)?$/', $value)
                        || preg_match('/^\d{4}-00-\d{2}(\s\d{2}:\d{2}:\d{2})?$/', $value)
                        || preg_match('/^\d{4}-\d{2}-00(\s\d{2}:\d{2}:\d{2})?$/', $value)
                    ) {
                        $newValue = $isNotNull ? (($type === 'date') ? '1970-01-01' : '1970-01-01 00:00:00') : null;
                        $conversionSummary[$table]['date'][$colNameLower] = ($conversionSummary[$table]['date'][$colNameLower] ?? 0) + 1;
                        return $newValue;
                    }
                    return $value;
                };

                foreach ($rows as &$row) {
                    foreach ($row as $key => &$value) {
                        $keyLower = strtolower($key);
                        if (!isset($columnTypes[$keyLower])) continue;

                        $type = $columnTypes[$keyLower];
                        $originalValue = $value;

                        // --- archived_tbl.added_by --- (Ensure it’s either 'admin' or 'user')
                        if ($table === 'archived_tbl' && $keyLower === 'added_by') {
                            if (empty(trim($value)) || !in_array(strtolower($value), ['admin', 'user'], true)) {
                                $value = 'user';  // Default to 'user' if invalid
                                $conversionSummary[$table]['check_constraint'][$keyLower] =
                                    ($conversionSummary[$table]['check_constraint'][$keyLower] ?? 0) + 1;
                            }
                            $value = trim($value); // Ensure no extra spaces
                        }

                        // --- archived_tbl.repeating_event --- (Ensure it's either 'Yes' or 'No')
                        if ($table === 'archived_tbl' && $keyLower === 'repeating_event') {
                            if (!in_array(strtoupper((string)$value), ['YES', 'NO'], true)) {
                                $value = 'No';  // Default to 'No' if invalid
                                $conversionSummary[$table]['check_constraint'][$keyLower] =
                                    ($conversionSummary[$table]['check_constraint'][$keyLower] ?? 0) + 1;
                            }
                        }

                        // --- archived_tbl.time_permission --- (Ensure it's either 'Yes' or 'No')
                        if ($table === 'archived_tbl' && $keyLower === 'time_permission') {
                            if (!in_array(strtoupper((string)$value), ['YES', 'NO'], true)) {
                                $value = 'No';  // Default to 'No' if invalid
                                $conversionSummary[$table]['check_constraint'][$keyLower] =
                                    ($conversionSummary[$table]['check_constraint'][$keyLower] ?? 0) + 1;
                            }
                        }

                        // --- submit_event_tbl.repeating_event ---
                        // Robust normalization that respects actual column type & constraint tokens if discovered.
                        if ($table === 'submit_event_tbl' && $keyLower === 'repeating_event') {
                            $originalRepeating = $value;
                            $val = $value;

                            // Normalize types like boolean true/false, MySQL 't'/'f', numeric 1/0 to strings first
                            if (is_bool($val)) {
                                $val = $val ? 'true' : 'false';
                            } elseif (is_numeric($val)) {
                                $val = (string)$val;
                            } elseif (is_null($val) || $val === '') {
                                $val = '';
                            } else {
                                $val = trim((string)$val);
                            }

                            // Determine the PG column type for repeating_event if available
                            $repeatingColType = $columnTypes['repeating_event'] ?? null;

                            // If constraint tokens were discovered earlier (submitRepeatingAllowedValues), use them
                            if (isset($submitRepeatingAllowedValues) && is_array($submitRepeatingAllowedValues) && !empty($submitRepeatingAllowedValues)) {
                                // Try exact, then case-insensitive matches to pick allowed token (preserve DB casing)
                                $chosen = null;
                                foreach ($submitRepeatingAllowedValues as $allowed) {
                                    if ((string)$val === (string)$allowed) {
                                        $chosen = $allowed;
                                        break;
                                    }
                                }
                                if ($chosen === null) {
                                    foreach ($submitRepeatingAllowedValues as $allowed) {
                                        if (strcasecmp((string)$val, (string)$allowed) === 0) {
                                            $chosen = $allowed;
                                            break;
                                        }
                                    }
                                }

                                // Map common boolean-like inputs to 'yes'/'no' concept then try match again
                                if ($chosen === null) {
                                    $lower = strtolower((string)$val);
                                    if (in_array($lower, ['1', 'true', 't', 'y', 'yes'], true)) $val = 'yes';
                                    elseif (in_array($lower, ['0', 'false', 'f', 'n', 'no'], true)) $val = 'no';

                                    foreach ($submitRepeatingAllowedValues as $allowed) {
                                        if (strcasecmp((string)$val, (string)$allowed) === 0) {
                                            $chosen = $allowed;
                                            break;
                                        }
                                    }
                                }

                                // fallback: use the first allowed token if nothing else matches (ensures constraint satisfied)
                                if ($chosen === null) $chosen = $submitRepeatingAllowedValues[0];

                                $value = $chosen;
                            } else {
                                // If column is boolean in PG, convert to boolean true/false
                                if ($repeatingColType === 'boolean') {
                                    $lower = strtolower((string)$val);
                                    if (in_array($lower, ['1', 'true', 't', 'y', 'yes'], true)) {
                                        $value = true;
                                    } elseif (in_array($lower, ['0', 'false', 'f', 'n', 'no'], true)) {
                                        $value = false;
                                    } else {
                                        // fallback for null/empty or unknown: use false if NOT NULL, else null
                                        $value = !empty($notNullColumns['repeating_event']) ? false : null;
                                    }
                                } else {
                                    // Fallback to string tokens, try to match common casing patterns preserving possibility DB expects 'Yes'/'No'
                                    $lower = strtolower((string)$val);
                                    if (in_array($lower, ['1', 'true', 't', 'y', 'yes'], true)) {
                                        // Prefer capitalized 'Yes' (common)
                                        $value = 'Yes';
                                    } elseif (in_array($lower, ['0', 'false', 'f', 'n', 'no'], true)) {
                                        $value = 'No';
                                    } else {
                                        // Keep original if empty or unknown; validation step will decide later
                                        $value = $val;
                                    }
                                }
                            }

                            if ($value !== $originalRepeating) {
                                $conversionSummary[$table]['check_constraint'][$keyLower] =
                                    ($conversionSummary[$table]['check_constraint'][$keyLower] ?? 0) + 1;
                            }
                        }

                        // --- Date sanitization ---
                        $value = $sanitizeDate($value, !empty($notNullColumns[$keyLower]), $type);

                        // --- Boolean fields ---
                        if ($type === 'boolean') {
                            if (!is_bool($value)) {
                                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                            }
                            if ($value === null && !empty($notNullColumns[$keyLower])) {
                                $value = false;
                                $conversionSummary[$table]['boolean'][$keyLower] =
                                    ($conversionSummary[$table]['boolean'][$keyLower] ?? 0) + 1;
                            }
                        }

                        // --- Numeric fields ---
                        if (in_array($type, ['smallint', 'integer', 'bigint']) && $value === '' && !isset($checkConstraintColumns[$keyLower])) {
                            $value = !empty($notNullColumns[$keyLower]) ? 0 : null;
                            if ($originalValue !== $value) {
                                $conversionSummary[$table]['numeric'][$keyLower] =
                                    ($conversionSummary[$table]['numeric'][$keyLower] ?? 0) + 1;
                            }
                        }

                        // --- Text fields ---
                        if (($type === 'character varying' || $type === 'text') && $value === '' && !isset($checkConstraintColumns[$keyLower])) {
                            $value = !empty($notNullColumns[$keyLower]) ? 'N/A' : null;
                            if ($originalValue !== $value) {
                                $conversionSummary[$table]['text'][$keyLower] =
                                    ($conversionSummary[$table]['text'][$keyLower] ?? 0) + 1;
                            }
                        }
                    }
                }

                // Replace TRUNCATE + batch insert with pre-validation to avoid a single bad row failing the batch
                $quotedPGTable = '"' . $table . '"';

                // Pre-validate rows to avoid batch failure: test repeating_event value vs allowed tokens / boolean type
                $validRows = [];
                $invalidRows = [];
                foreach ($rows as $rIdx => $r) {
                    $isValid = true;

                    // Only validate repeating_event for submit_event_tbl
                    if ($table === 'submit_event_tbl') {
                        $pgColType = $columnTypes['repeating_event'] ?? null;
                        $val = array_key_exists('repeating_event', $r) ? $r['repeating_event'] : null;

                        if ($pgColType === 'boolean') {
                            // check if value can be interpreted as boolean
                            if (!is_bool($val)) {
                                $parsed = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                                if ($parsed === null && !empty($notNullColumns['repeating_event'])) {
                                    $isValid = false;
                                }
                            }
                        } elseif (is_array($submitRepeatingAllowedValues) && !empty($submitRepeatingAllowedValues)) {
                            $found = false;
                            foreach ($submitRepeatingAllowedValues as $allowed) {
                                if ((string)$val === (string)$allowed || strcasecmp((string)$val, (string)$allowed) === 0) {
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) $isValid = false;
                        } else {
                            // If we don't know allowed tokens, attempt to accept common 'Yes'/'No' / 'YES'/'NO'
                            $lower = strtolower((string)$val);
                            if (!in_array($lower, ['yes', 'no', '1', '0', 'true', 'false', 't', 'f', 'y', 'n', ''], true)) {
                                $isValid = false;
                            }
                        }
                    }

                    if ($isValid) $validRows[] = $r;
                    else $invalidRows[] = ['index' => $rIdx, 'row' => $r];
                }

                if (!empty($invalidRows)) {
                    log_message('warning', "Table {$table} has " . count($invalidRows) . " rows failing pre-validation; they will be skipped.");
                    foreach ($invalidRows as $inv) {
                        CLI::write("Skipped invalid row {$inv['index']} in {$table}", 'red');
                    }
                }

                $pgsql->query("TRUNCATE TABLE {$quotedPGTable} RESTART IDENTITY CASCADE");

                $batchSize = 1000;
                $totalRows = count($validRows);
                for ($offset = 0; $offset < $totalRows; $offset += $batchSize) {
                    $chunk = array_slice($validRows, $offset, $batchSize);
                    if (!empty($chunk)) {
                        $pgsql->table($quotedPGTable)->insertBatch($chunk);
                    }
                    CLI::write("Inserted rows {$offset} - " . ($offset + count($chunk)) . " into {$table}", 'blue');
                }

                /**
                 * ----------------------------------------------------
                 * PATCH 2: Sync the sequence AFTER inserts
                 * ----------------------------------------------------
                 */
                $this->syncSequenceForTable($pgsql, $table);

                CLI::write("✓ Synced: {$table}", 'green');
                log_message('info', "Table synced: {$table}");
            } catch (\Throwable $e) {
                CLI::error("Error syncing {$table}: " . $e->getMessage());
                log_message('error', "Table Sync Error [{$table}]: " . $e->getMessage());
            }
        }

        CLI::write("\nConversion Summary:", 'light_green');
        foreach ($conversionSummary as $table => $types) {
            CLI::write("Table: {$table}", 'yellow');
            foreach ($types as $type => $cols) {
                foreach ($cols as $col => $count) {
                    CLI::write("  {$type} conversions for {$col}: {$count}", 'cyan');
                }
            }
        }

        CLI::write("\nAll tables synced successfully!", 'light_green');
        log_message('info', "Full DB Sync Completed with conversion summary.");
    }
}
