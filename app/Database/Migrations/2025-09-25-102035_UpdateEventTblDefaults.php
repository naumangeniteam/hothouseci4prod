<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateEventTblDefaults extends Migration
{
    public function up()
    {
        $this->db->query("
            ALTER TABLE public.event_tbl
            DROP CONSTRAINT IF EXISTS event_tbl_repeating_event_check,
            ADD CONSTRAINT event_tbl_repeating_event_check
            CHECK (LOWER(repeating_event) = ANY (ARRAY['yes','no']));
        ");

        $this->db->query("
            ALTER TABLE public.event_tbl
            DROP CONSTRAINT IF EXISTS event_tbl_time_permission_check,
            ADD CONSTRAINT event_tbl_time_permission_check
            CHECK (LOWER(time_permission) = ANY (ARRAY['yes','no']));
        ");

        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN created_by SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN set_time SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN cover_charge SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN repeating_event SET DEFAULT 'no';");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN time_permission SET DEFAULT 'no';");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN no_of_repeat SET DEFAULT 0;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN virtual_event_price SET DEFAULT 0;");
        // drop not null for added_by 
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN added_by DROP NOT NULL;");
    }

    public function down()
    {

        $this->db->query("
            ALTER TABLE public.event_tbl
            DROP CONSTRAINT IF EXISTS event_tbl_repeating_event_check,
            ADD CONSTRAINT event_tbl_repeating_event_check
            CHECK (repeating_event = ANY (ARRAY['Yes','No']));
        ");

        $this->db->query("
            ALTER TABLE public.event_tbl
            DROP CONSTRAINT IF EXISTS event_tbl_time_permission_check,
            ADD CONSTRAINT event_tbl_time_permission_check
            CHECK (time_permission = ANY (ARRAY['Yes','No']));
        ");

        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN created_by DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN set_time DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN cover_charge DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN repeating_event DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN time_permission DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN no_of_repeat DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN virtual_event_price DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_tbl ALTER COLUMN added_by SET NOT NULL;");
    }
}
