<?php
class Users {

    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function change_password($user_id, $password) {

        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = $this->db->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");

        $query->bindValue(1, $password);
        $query->bindValue(2, $user_id);

        try {
            $query->execute();
            return true;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function user_exists($email) {

        $query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `email`= ?");
        $query->bindValue(1, $email);

        try {

            $query->execute();
            $rows = $query->fetchColumn();

            if ($rows == 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function register($first_name, $last_name, $email, $password, $access_level, $address_1, $address_2, $city, $state, $post_code, $country, $phone) {

        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = $this->db->prepare("INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `access_level`, `address_1`, `address_2`, `city`, `state`, `post_code`, `country`, `phone`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");

        $query->bindValue(1, $first_name);
        $query->bindValue(2, $last_name);
        $query->bindValue(3, $email);
        $query->bindValue(4, $password);
        $query->bindValue(5, $access_level);
        $query->bindValue(6, $address_1);
        $query->bindValue(7, $address_2);
        $query->bindValue(8, $city);
        $query->bindValue(9, $state);
        $query->bindValue(10, $post_code);
        $query->bindValue(11, $country);
        $query->bindValue(12, $phone);

        try {
            $query->execute();

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function login($email, $password) {

        $query = $this->db->prepare("SELECT `password`, `id`, `access_level`, `display_name` FROM `users` WHERE `email` = ?");
        $query->bindValue(1, $email);

        try {

            $query->execute();
            $data = $query->fetch();
            $stored_password = $data['password']; // stored hashed password
            $id = $data['id']; // id of the user to be returned if the password is verified, below.

            if (password_verify($password, $stored_password) === true) { // using the verify method to compare the password with the stored hashed password.
                $_SESSION['access_level'] = $data['access_level'];
                $_SESSION['id'] = $data['id'];
                $_SESSION['display_name'] = $data['display_name'];
                return $id; // returning the user's id.
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function logged_in() {
        return(isset($_SESSION['id'])) ? true : false;
    }

    public function logged_in_protect() {
        if ($this->logged_in() === true) {
            header('Location: /');
            exit();
        }
    }

    public function logged_out_protect() {
        if ($this->logged_in() === false) {
            header('Location: /login.php');
            exit();
        }
    }

    public function get_users() {

        $query = $this->db->prepare("SELECT * FROM `users` ORDER BY `id` ASC");

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll();
    }
    public function delete_user($id) {
        $query = $this->db->prepare("DELETE from `users` where `id` = ?");

        $query->bindValue(1, $id);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function get_user($id) {
        $query = $this->db->prepare("SELECT * FROM `users` WHERE `id`= ?");
        $query->bindValue(1, $id);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetch();
    }

    public function update_profile($email, $password, $first_name, $last_name, $display_name, $id) {
        $query = $this->db->prepare("UPDATE `users` SET `email` = ?, `password` = ?, `first_name` = ?, `last_name` = ?, `display_name` = ? WHERE `id` = ?");

        $password = password_hash($password, PASSWORD_DEFAULT);

        $query->bindValue(1, $email);
        $query->bindValue(2, $password);
        $query->bindValue(3, $first_name);
        $query->bindValue(4, $last_name);
        $query->bindValue(5, $display_name);
        $query->bindValue(6, $id);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

}