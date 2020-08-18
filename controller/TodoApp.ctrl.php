<?php

//DECLERATION FOR INTEGER AND STRING WARNING
declare(strict_types=1);

//DECLARE PATH NAME
// function url(){
//     return sprintf(
//       "%s://%s%s",
//       isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
//       $_SERVER['SERVER_NAME'],"/www/todoapp"
//     );
//   }
define("PATH_NAME","../");
// define("PATH_NAME", realpath($_SERVER["DOCUMENT_ROOT"])."/todoapp");
// define("PATH_NAME", "../todoapp");

//SET DATE TIME ZONE
date_default_timezone_set('Asia/Kolkata');


//INCLUDE DATABASE CONNECTIVITY
include_once('../model/Database.model.php');

class TodoApp extends Database
{
    private $table_name;

    public function __construct($table_name)
    {
        //FIRST CHECK THAT THE TABLE EXISTS OR NOT
        $this->table_name = $table_name;
        if ($this->table_name === 'todos') {
            $sql = "CREATE TABLE IF NOT EXISTS `$this->table_name` (
            `id` int(100) NOT NULL AUTO_INCREMENT,
            `user_id` int(100) NOT NULL,
            `title` varchar(255) NOT NULL,
            `note` varchar(3000) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (ID)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $stmt_table = $this->connect()->query($sql) or die("ERROR TO CREATE TABLE");
        } else if ($this->table_name === 'users') {
            $sql = "CREATE TABLE IF NOT EXISTS `$this->table_name` (
                `id` int(100) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `password` varchar(3000) NOT NULL,
                `ptoken` varchar(255) NOT NULL,
                `password_change` varchar(100) NOT NULL DEFAULT 0,
                `phone` varchar(255) NOT NULL,
                `gender` varchar(255) NOT NULL,
                `dob` varchar(255) NOT NULL,
                `img_path` varchar(255) NOT NULL,
                `vtoken` varchar(255) NOT NULL,
                `email_verified` int(10) NOT NULL DEFAULT 0, 
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (ID),
                UNIQUE(`email`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $stmt_table = $this->connect()->query($sql) or die("ERROR TO CREATE TABLE");
        } else {
            die("PLEASE ENTER CORRECT TABLE NAME");
        }
    }

    //GET DATA
    public function getData($select_column_field = [], $conditionArr = [], $order_by_field = '', $order_by_type = 'ASC', $limit = '')
    {
        //SELECT * (OR NAME='SUBIR' AND ID='1') FROM TODOS 
        //WHERE ID='1' AND NAME='SUBIR'
        //ORDER BY NAME  ASC LIMIT 1;

        //SELECT  $FIELD FROM $THIS->TABLE WHERE $CONDITION $ORDER_BY_FIELD
        //$ORDER_BY_TYPE LIMIT $LIMIT;

        //HERE $FIELD = * BY DEFAULT OR NAME='SUBIR' AND ID='1'
        //$ORDER_BY_FIELD = NAME OR EMAIL OR PHONE ETC
        //$ORDER_BY_TYPE = ASC OR DESC BY DEFALUT ASC

        //$sql = "SELECT $field FROM $this->table_name WHERE $condition $order_by_field $order_by_type LIMIT $limit ";
        if (empty($select_column_field)) {
            $select_column_field = '*';
            $sql = " SELECT $select_column_field FROM $this->table_name ";
        } else {
            $sql = " SELECT ";
            $i = 1;
            foreach ($select_column_field as $key) {
                if (count($select_column_field) === $i) {
                    $sql .= " $key ";
                } else {
                    $sql .= " $key , ";
                }
                $i++;
            }
            $sql .= " FROM $this->table_name ";
        }



        if (!empty($conditionArr)) {
            $sql .= " WHERE ";
            $c = 1;
            foreach ($conditionArr as $key => $val) {

                if (count($conditionArr) === $c) {
                    $sql .= " $key = ? ";
                } else {
                    $sql .= " $key = ? AND ";
                }
                $c++;

                $valueArr[] = $val;
                $valueTypeArr[] = gettype($val)[0];
            }
        }

        if ($order_by_field != '') {
            $sql .= " ORDER BY $order_by_field $order_by_type ";
        }
        if ($limit != '') {
            $sql .= " LIMIT $limit ";
        }

        // echo $sql;die;
        //PREPARE STATEMENT
        $stmt = $this->connect()->prepare($sql);


        if (!empty($conditionArr)) {
            $valueType = implode("", $valueTypeArr);
            $stmt->bind_param($valueType, ...$valueArr);
        }

        //EXECUTE STATEMENT
        $stmt->execute();
        return $stmt->get_result();
    }



    //INSERT DATA
    public function insertData($insertDataArr)
    {
        if (!empty($insertDataArr)) {
            foreach ($insertDataArr as $key => $val) {
                // $key = mysqli_real_escape_string($this->connect(), $key);
                // $val = mysqli_real_escape_string($this->connect(), $val);
                $fieldArr[] = $key;
                $valueArr[] = $val;
                $valQueArr[] = '?';
                $valArrType[] = gettype($val)[0];
            }

            $field = implode(" , ", $fieldArr);
            $valueQue = implode(" , ", $valQueArr);
            $valueType = implode("", $valArrType);

            $sql = "INSERT INTO $this->table_name ($field) VALUES ($valueQue)";

            //PREPARE STATEMENT
            $stmt = $this->connect()->prepare($sql);

            //BINDING STATEMENT ... FOR GIVEN PASSING ARRAY VALUE
            $stmt->bind_param($valueType, ...$valueArr);

            //EXECUTE THE STATEMENT 
            if ($stmt->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }


    //UPDATE DATA
    public function updateData($idArr, $updateDataArr)
    {
        if (!empty($updateDataArr) && !empty($idArr)) {
            foreach ($idArr as $key => $val) {
                $key = mysqli_real_escape_string($this->connect(),$key);
                $id = $key . ' = ? ';
                $idValue = $val;
                $idvalType = gettype($val)[0];
            }

            foreach ($updateDataArr as $key => $value) {
                $key = mysqli_real_escape_string($this->connect(),$key);
                // $value = mysqli_real_escape_string($this->connect(),$value);

                $fieldArr[] = $key . ' = ? ';
                $valueArr[] = $value;
                $valType[] = gettype($value)[0];
            }

            $field = implode(" , ", $fieldArr);

            $sql = "UPDATE $this->table_name SET $field WHERE $id";
            $stmt = $this->connect()->prepare($sql);

            //FOR VALUETYPE
            array_push($valType, $idvalType);
            $valType = implode("", $valType);
            //FOR VALUE
            array_push($valueArr, $idValue);

            $stmt->bind_param($valType, ...$valueArr);

            if ($stmt->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }



    //DELETE DATA
    public function deleteData($id,$val)
    {
        if (!empty($id)) {
            // foreach ($idArr as $key => $val) {
            //     // $key = mysqli_real_escape_string($this->connect(),$key);
                
            //     $id = $key;
            //     foreach ($val as $k => $v) {
            //         // $k = mysqli_real_escape_string($this->connect(),$k);
            //         // $v = mysqli_real_escape_string($this->connect(),$v);

            //         $idvalArr[] = $v;
            //         $idvalQueArr[] = '?';
            //         $idvalTypeArr[] = gettype($v)[0];
            //     }
            // }

            // $idvalQue = implode(" , ", $idvalQueArr);
            // $idvalType = implode("", $idvalTypeArr);

            $sql = " DELETE FROM $this->table_name WHERE $id  = ? ";

            $stmt = $this->connect()->prepare($sql);

            $stmt->bind_param('i', $val);

            if ($stmt->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    //DESTRUCT METHOD
    public function __destruct()
    {
        $this->connect()->close();
    }
}

// echo "jhb";
// $todo = new TodoApp('users');
// $condition = ['user_id'=>10,'title' => 'hi user 10', 'note' => 'hi note 10'];
// $condition = ['name'=>'Dakshi Sweets','email'=>'sfhj@gmail.com'];
// $data = $todo->getData();
// print_r($data->fetch_assoc());

// $data = $todo->insertData( $condition);
// $data = $todo->deleteData('id',1);
// $data = $todo->updateData(['id'=>1],$condition);
// if ($data->num_rows > 0) {
//     while ($row = $data->fetch_assoc()) {
//         echo '<pre>';
//         print_r($row);
//     }
// } else {
//     $data = "NOT FOUND";
// }
// echo '<pre>';
// print_r($data);
// $data = $todo->singleTodList(1);

// if($data->num_rows>0){
//     print_r($data->fetch_assoc());
// }else{
//     echo "NO RESULT FOUND";
// }
// print_r($data->fetch_assoc());

// $res = $todo->createTodoList(2,"Kal ke porte bosbo","kal firste math then sql dupure tarpor rate abar math tarpor khawa hoe gele abar website design krbo");
// if($res){
//     echo "DATA INSERTED SUCCESSFULLY";
// }else{
//     echo "NOT INSERTED AT THE MOMENT";
// }