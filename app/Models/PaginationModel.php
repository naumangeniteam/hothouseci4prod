<?php

namespace App\Models;

use CodeIgniter\Model;

class PaginationModel extends Model
{
    protected $table = 'blog_tbl';
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [];
    
    // Dates
    protected $useTimestamps = false;
    
    /**
     * Get total count of records
     *
     * @return int
     */
    public function get_count()
    {
        return $this->countAll();
    }
    
    /**
     * Get records in ascending order by position
     *
     * @return array
     */
    public function get_desc()
    {
        return $this->orderBy('position', 'ASC')
                    ->get()
                    ->getResultArray();
    }
    
    /**
     * Get paginated authors sorted by position
     *
     * @param int $limit Number of records to fetch
     * @param int $start Offset to start from
     * @return array
     */
    public function get_authors($limit, $start)
    {
        return $this->orderBy('position', 'ASC')
                    ->limit($limit, $start)
                    ->get()
                    ->getResult();
    }
}