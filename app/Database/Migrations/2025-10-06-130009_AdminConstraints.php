<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdminConstraints extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN creation_ip DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN update_ip DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN update_date DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN updated_by DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN last_login_date DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN last_login_ip DROP NOT NULL;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN creation_ip SET NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN update_ip SET NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN update_date SET NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN updated_by SET NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN last_login_date SET NOT NULL;");
        $this->db->query("ALTER TABLE public.admin ALTER COLUMN last_login_ip SET NOT NULL;");
    }
}
