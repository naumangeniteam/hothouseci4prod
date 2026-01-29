<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateEventLocationTblDefaults extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN description SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN find_location SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN zipcode SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN short_description SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN location_type SET DEFAULT 0;");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN ip_address SET DEFAULT '';");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN created_by SET DEFAULT 0;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN short_description DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN find_location DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN zipcode DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN description DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN location_type DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN ip_address DROP DEFAULT;");
        $this->db->query("ALTER TABLE public.event_location_tbl ALTER COLUMN created_by DROP DEFAULT;");
    }
}
