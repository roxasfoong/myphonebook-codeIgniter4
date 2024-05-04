<?php

namespace App\Models;

use CodeIgniter\Model;

class Contact_model extends Model
{
    protected $table = 'contacts';
    protected $allowedFields = ['id','user_id', 'name', 'address', 'email', 'phone_number', 'image_location', 'remark'];

    public function load_database() {

        // Load the database connection
        $db = db_connect();

        // Check if the connection is successful
        if ($db->connect_errno) {
            return false;
        } else {
            // Connection successful
            return true;
        }
    }

    // Function to insert a new contact into the database
    public function insert_contact($data)
    {
        return $this->insert($data);
    }

    // Function to retrieve all contacts from the database
    public function get_recently_added_contact($user_id)
    {
        return $this->where('user_id', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    public function get_image_location($contact_id)
    {
        $image_row = $this->select('image_location')
                          ->where('id', $contact_id)
                          ->get()
                          ->getRow();
        return $image_row->image_location ?? null;
    }

    public function get_all_contacts($user_id)
    {
        $result = $this->where('user_id', $user_id)
        ->orderBy('created_at', 'ASC')
        ->findAll();

        if (empty($result)) {
        return null;
        }

        foreach ($result as &$contact) {
        unset($contact['id']);
        unset($contact['user_id']);
    }

    return $result;

    }



    public function count_total_row($user_id){

           return $this->where('user_id', $user_id)
                ->countAllResults();

    }

    public function get_total_number_of_page($user_id)
    {
        $total = $this->where('user_id', $user_id)
        ->countAllResults();

if (empty($total)) {
return 0;
}

$result = ($total % 8 > 0) ? (int)($total / 8) + 1 : (int)($total / 8);

return $result;

    }


    public function generatePagination($totalPages, $current_Page) {
        $totalPages = intval($totalPages);
        $currentPage = intval($current_Page);
    
        $paginationHTML = '';
    
        if ($totalPages > 1) {
            if ($currentPage > 1) {
                $paginationHTML .= '<button onclick="gotoPage(' . ($currentPage - 1) . ')">&lt;</button>';
            }
            if ($currentPage > 1) {
                $paginationHTML .= '<button onclick="gotoPage(' . ($currentPage - 1) . ')">' . ($currentPage - 1) . '</button>';
            }
            $paginationHTML .= '<strong>' . $currentPage . '</strong>';
            if ($currentPage < $totalPages) {
                $paginationHTML .= '<button onclick="gotoPage(' . ($currentPage + 1) . ')">' . ($currentPage + 1) . '</button>';
            }
            if ($currentPage < $totalPages) {
                $paginationHTML .= '<button onclick="gotoPage(' . ($currentPage + 1) . ')">&gt;</button>';
            }
        }
    
        return $paginationHTML;
    }

    public function count_max_page($user_id)
    {

        $totalRows = $this->count_total_row($user_id);

        if ($totalRows == 0 || $totalRows < 8) {
            return 1;
        } else {
            return ($totalRows % 8 > 0) ? (int)($totalRows / 8) + 1 : $totalRows / 8;
        }

    }

    public function compare_page_number($user_id,$current_page)
    {

    $newPage = min($current_page, $this->count_max_page($user_id));
    return max($newPage, 1);

    }

    public function get_all_contacts_with_offset($user_id, $current_page)
    {
        
    $totalRows = $this->count_total_row($user_id);

    if ($totalRows == 0) {
        return null;
    }

    $maxPageNumber = $this->count_max_page($user_id);
    $startFrom = ($current_page - 1) * 8;

    if ($startFrom >= $totalRows) {
        $startFrom = ($maxPageNumber - 1) * 8;
    }

    $result = $this->where('user_id', $user_id)
                   ->orderBy('created_at', 'ASC')
                   ->limit(8, $startFrom)
                   ->findAll();

    foreach ($result as &$contact) {
        unset($contact['id']);
        unset($contact['user_id']);
    }

    return $result;
    }

    public function get_contact_by_id($contact_id)
    {
        return $this->where('id', $contact_id)->first();
    }

    public function update_contact($contact_id, $data)
    {
        $contact_id = intval($contact_id);
        $image_row = $this->select('image_location')
                          ->where('id', $contact_id)
                          ->get()
                          ->getRow();
        $image_location = $image_row->image_location; 


$result = $this->db->table('contacts')->where('id', $contact_id)->update($data);

if ($result && $image_location !== $data['image_location'] && $image_location !== '/assets/img/empty-profile-picture.webp') {
unlink(FCPATH . $image_location);


}

return $result;

    }

    // Function to delete a contact from the database
    public function delete_contact_by_id($contact_id)
    {
        return $this->where('id', $contact_id)->delete();

    }

    public function delete_contact_by_phone_with_user_id($phone_number,$user_id)
    {
    $image_location = $this->select('image_location')
                           ->where('user_id', $user_id)
                           ->where('phone_number', $phone_number)
                           ->get()
                           ->getRow()
                           ->image_location;

    $result = $this->where('user_id', $user_id)
         ->where('phone_number', $phone_number)
         ->delete();

         if($result){
    if ($this->affectedRows() > 0 && $image_location !== '/assets/img/empty-profile-picture.webp') {
        unlink(FCPATH . $image_location);
        
    }
    return true;
    }

    return false;
    }

    public function get_contact_by_phone_with_user_id($phone_number, $user_id)
    {

        $result = $this->where('phone_number', $phone_number)
        ->where('user_id', $user_id)
        ->first();

if ($result) {
unset($result['id']);
unset($result['user_id']);
unset($result['created_at']);
unset($result['updated_at']);
}

return $result;

    }

    public function get_contact_id($phone_number, $user_id)
    {
        $result = $this->select('id')
        ->where('user_id', $user_id)
        ->where('phone_number', $phone_number)
        ->limit(1)
        ->get()
        ->getRow();

        return $result ? $result->id : null;

    }

    private function putToLog($data)
    {

        $file = fopen(FCPATH . 'log/log.txt', 'w');
        ob_start();
        var_dump($data);
        fwrite($file, ob_get_clean());
        fclose($file);

    }

}
