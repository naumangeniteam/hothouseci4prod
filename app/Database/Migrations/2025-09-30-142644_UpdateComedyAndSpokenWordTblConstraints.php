<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateComedyAndSpokenWordTblConstraints extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN no_of_repeat DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN no_of_repeat SET DEFAULT 0;");
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN virtual_event_price SET DEFAULT 0;");
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN added_by DROP NOT NULL;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN no_of_repeat SET NOT NULL;");
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN no_of_repeat DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN virtual_event_price DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.comedyandspokenword_tbl ALTER COLUMN added_by SET NOT NULL;");
    }
}
