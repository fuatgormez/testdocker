<?php

namespace ttact;

/**
 * Database short summary.
 *
 * Database description.
 *
 * @version 1.0
 * @author Mian
 */
class Database
{
    private $db;

    public function __construct($db_host, $db_user, $db_pass, $db_name)
    {
        $this->db = new \mysqli($db_host, $db_user, $db_pass, $db_name);
        $this->db->set_charset("utf8");
    }

    public function getFirstRow(string $table, array $where = [], array $fields = [], array $order_by = [], string $asc_or_desc = 'ASC')
    {
        $rows = $this->getRows($table, $fields, $where, $order_by, $asc_or_desc, 1);
        if (isset($rows[0])) {
            return $rows[0];
        }
        return [];
    }

    /**
     * @return array
     */
    public function getRows(string $table, array $fields = [], array $where = [], array $order_by = [], string $asc_or_desc = 'ASC', int $limit = -1)
    {
        // generate mysql query statement base with table and fields
        $stmt_query = "SELECT ";
        $return_array = [];

        if (count($fields) > 0) {
            $i = 0;
            foreach ($fields as $index => $value) {
                if (is_int($index)) {
                    $stmt_query .= "`" . $value . "`";
                } else {
                    $stmt_query .= "`" . $index . "` AS `" . $value . "`";
                }

                if ($i < (count($fields) - 1)) {
                    $stmt_query .= ", ";
                }
                $i++;
            }
        } else {
            $stmt_query .= "*";
        }

        $stmt_query.= " FROM `" . $table . "`";

        // add where clauses with questionmark-placeholders instead of values
        $stmt_values = [];
        $add_where_clause = false;

        if (!is_array($where)) {
            return [];
        } elseif (count($where) > 0) {
            foreach ($where as $index => $value) {
                if (is_int($index)) {
                    if (is_array($value)) {
                        if (count($value) == 3) {
                            if (!(is_string($value[0]) || is_int($value[0])) || !(is_string($value[1]) || is_int($value[1])) || !(is_string($value[2]) || is_int($value[2]))) {
                                return [];
                            } else {
                                $add_where_clause = true;
                            }
                        } elseif (count($value) == 2) {
                            if (!is_string($value[0]) || !is_array($value[1])) {
                                return [];
                            } elseif (count($value[1]) < 1) {
                                return [];
                            } else {
                                $add_where_clause = true;
                            }
                        } else {
                            return [];
                        }
                    } else {
                        return [];
                    }
                } elseif (is_string($index)) {
                    if (is_string($value) || is_int($value)) {
                        $add_where_clause = true;
                    } else {
                        return [];
                    }
                } else {
                    return [];
                }
            }
        } else {
            // no where clauses...
        }

        if ($add_where_clause) {
            $stmt_query .= " WHERE ";
            $i = 0;
            foreach ($where as $index => $value) {
                if (is_int($index)) {
                    if (count($value) == 2) {
                        $stmt_query .= "`" . $value[0] . "` IN (";
                        $ii = 0;
                        foreach ($value[1] as $val) {
                            $stmt_query .= "?";
                            $stmt_values[] = $val;

                            if ($ii < (count($value[1]) - 1)) {
                                $stmt_query .= ", ";
                            }

                            $ii++;
                        }
                        $stmt_query .= ")";
                    } else {
                        $stmt_query .= "`" . $value[0] . "` " . $value[1] . " ?";
                        $stmt_values[] = $value[2];
                    }
                } else {
                    $stmt_query .= "`" . $index . "` = ?";
                    $stmt_values[] = $value;
                }

                if ($i < (count($where) - 1)) {
                    $stmt_query .= " AND ";
                }

                $i++;
            }
        }

        // order by
        if (count($order_by) > 0) {
            $stmt_query .= " ORDER BY ";

            $i = 0;
            foreach ($order_by as $a) {
                $stmt_query .= "`" . $a . "`";

                if ($i < (count($order_by) - 1)) {
                    $stmt_query .= ", ";
                }

                $i++;
            }

            $stmt_query .= " " . $asc_or_desc;
        }

        // limit
        if ($limit > 0) {
            $limit = (int) $limit;
            $stmt_query .= " LIMIT " . $limit;
        }

        // replace questionmark-placeholders with values
        $mysql_result = null;

        if (count($stmt_values) > 0) {
            if ($stmt = $this->db->prepare($stmt_query)) {
                $types = "";

                for ($i = 1; $i <= count($stmt_values); $i++) {
                    $types .= "s";
                }

                array_unshift($stmt_values, $types);

                $ref_values = [];
                foreach ($stmt_values as $key => $value) {
                    $ref_values[$key] = &$stmt_values[$key];
                }

                call_user_func_array(array($stmt, "bind_param"), $ref_values);

                $stmt->execute();

                $mysql_result = $stmt->get_result();

                $stmt->close();
            }
        } else {
            $mysql_result = $this->query($stmt_query);
        }

        if ($mysql_result instanceof \mysqli_result) {
            while ($row = $mysql_result->fetch_assoc()) {
                $return_array[] = $row;
            }
        }

        // return values
        return $return_array;
    }

    /**
     * @return array
     */
    public function getRowsQuery(string $query)
    {
        $return_array = [];

        $mysql_result = $this->query($query);

        if ($mysql_result instanceof \mysqli_result) {
            while ($row = $mysql_result->fetch_assoc()) {
                $return_array[] = $row;
            }
        }

        // return values
        return $return_array;
    }

    public function getFieldNames(string $table)
    {
        $fields = [];

        $sql = "SELECT * FROM `" . $table . "` LIMIT 1";
        if ($mysql_result = $this->query($sql)) {
            $fields_info = $mysql_result->fetch_fields();
            foreach ($fields_info as $val) {
                $fields[] = $val->name;
            }
        }

        return $fields;
    }

    /**
     * @return int
     */
    public function getNumOfRows(string $table, array $where = [])
    {
        // generate mysql query statement base with table and fields
        $stmt_query = "SELECT ";
        $return = -1;

        $stmt_query .= "`" . $table . "_id`";

        $stmt_query.= " FROM `" . $table . "`";

        // add where clauses with questionmark-placeholders instead of values
        $stmt_values = [];
        $add_where_clause = false;

        if (!is_array($where)) {
            return [];
        } elseif (count($where) > 0) {
            foreach ($where as $index => $value) {
                if (is_int($index)) {
                    if (is_array($value)) {
                        if (count($value) == 3) {
                            if (!(is_string($value[0]) || is_int($value[0])) || !(is_string($value[1]) || is_int($value[1])) || !(is_string($value[2]) || is_int($value[2]))) {
                                return [];
                            } else {
                                $add_where_clause = true;
                            }
                        } elseif (count($value) == 2) {
                            if (!is_string($value[0]) || !is_array($value[1])) {
                                return [];
                            } elseif (count($value[1]) < 1) {
                                return [];
                            } else {
                                $add_where_clause = true;
                            }
                        } else {
                            return [];
                        }
                    } else {
                        return [];
                    }
                } elseif (is_string($index)) {
                    if (is_string($value) || is_int($value)) {
                        $add_where_clause = true;
                    } else {
                        return [];
                    }
                } else {
                    return [];
                }
            }
        } else {
            // no where clauses...
        }

        if ($add_where_clause) {
            $stmt_query .= " WHERE ";
            $i = 0;
            foreach ($where as $index => $value) {
                if (is_int($index)) {
                    if (count($value) == 2) {
                        $stmt_query .= "`" . $value[0] . "` IN (";
                        $ii = 0;
                        foreach ($value[1] as $val) {
                            $stmt_query .= "?";
                            $stmt_values[] = $val;

                            if ($ii < (count($value[1]) - 1)) {
                                $stmt_query .= ", ";
                            }

                            $ii++;
                        }
                        $stmt_query .= ")";
                    } else {
                        $stmt_query .= "`" . $value[0] . "` " . $value[1] . " ?";
                        $stmt_values[] = $value[2];
                    }
                } else {
                    $stmt_query .= "`" . $index . "` = ?";
                    $stmt_values[] = $value;
                }

                if ($i < (count($where) - 1)) {
                    $stmt_query .= " AND ";
                }

                $i++;
            }
        }

        // replace questionmark-placeholders with values
        $mysql_result = null;

        if (count($stmt_values) > 0) {
            if ($stmt = $this->db->prepare($stmt_query)) {
                $types = "";

                for ($i = 1; $i <= count($stmt_values); $i++) {
                    $types .= "s";
                }

                array_unshift($stmt_values, $types);

                $ref_values = [];
                foreach ($stmt_values as $key => $value) {
                    $ref_values[$key] = &$stmt_values[$key];
                }

                call_user_func_array(array($stmt, "bind_param"), $ref_values);

                $stmt->execute();

                $mysql_result = $stmt->get_result();

                $stmt->close();
            }
        } else {
            $mysql_result = $this->query($stmt_query);
        }

        if ($mysql_result instanceof \mysqli_result) {
            $return = $mysql_result->num_rows;
        }

        // return values
        return $return;
    }

    /**
     * @return int The MySQL insert id. If the insert was unsuccessful, the value -1 is returned.
     */
    public function insert(string $table, array $fields_to_values)
    {
        // generate mysql query statement with table and fields with placeholders for values
        $stmt_query = "INSERT INTO `" . $table . "` (";
        $stmt_values = [];

        if (count($fields_to_values) > 0) {
            $i = 0;
            foreach ($fields_to_values as $field => $value) {
                $stmt_query .= "`" . $field . "`";
                if ($i < (count($fields_to_values) - 1)) {
                    $stmt_query .= ", ";
                }
                $i++;
            }
        } else {
            return -1;
        }

        $stmt_query .= ") VALUES (";

        $i = 0;
        foreach ($fields_to_values as $field => $value) {
            $stmt_query .= "?";
            $stmt_values[] = $value;

            if ($i < (count($fields_to_values) - 1)) {
                $stmt_query .= ", ";
            }
            $i++;
        }

        $stmt_query .= ")";

        // replace placeholders with values
        if ($stmt = $this->db->prepare($stmt_query)) {
            $types = "";

            for ($i = 1; $i <= count($stmt_values); $i++) {
                $types .= "s";
            }

            array_unshift($stmt_values, $types);

            $ref_values = [];
            foreach ($stmt_values as $key => $value) {
                $ref_values[$key] = &$stmt_values[$key];
            }

            call_user_func_array(array($stmt, "bind_param"), $ref_values);

            if ($stmt->execute()) {
                $insert_id = $stmt->insert_id;
                $stmt->close();
                return $insert_id;
            }

            $stmt->close();
        }
        return -1;
    }

    /**
     * @return boolean successful or not
     */
    public function delete(string $table, int $where_id)
    {
        $stmt_query = "DELETE FROM `" . $table . "` WHERE `" . $table . "_id` = ?";

        if ($stmt = $this->db->prepare($stmt_query)) {
            $stmt->bind_param('i', $where_id);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }
            $stmt->close();
        }
        return false;
    }

    /**
     * @return boolean successful or not
     */
    public function update(string $table, int $where_id, array $fields_to_values)
    {
        // generate mysql query statement with table and fields with placeholders for values
        $stmt_query = "UPDATE `" . $table . "` SET ";
        $stmt_values = [];

        if (count($fields_to_values) > 0) {
            $i = 0;
            foreach ($fields_to_values as $field => $value) {
                $stmt_query .= "`" . $field . "` = ?";
                $stmt_values[] = $value;

                if ($i < (count($fields_to_values) - 1)) {
                    $stmt_query .= ", ";
                }
                $i++;
            }
        } else {
            return false;
        }

        // add condition with placeholder
        if ($where_id > 0) {
            $stmt_query .= " WHERE `" . $table . "_id` = ?";
            $stmt_values[] = (int) $where_id;
        } else {
            return false;
        }

        // replace placeholders with values
        if ($stmt = $this->db->prepare($stmt_query)) {
            $types = "";

            for ($i = 1; $i <= count($stmt_values); $i++) {
                $types .= "s";
            }

            array_unshift($stmt_values, $types);

            $ref_values = [];
            foreach ($stmt_values as $key => $value) {
                $ref_values[$key] = &$stmt_values[$key];
            }

            call_user_func_array(array($stmt, "bind_param"), $ref_values);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }

            $stmt->close();
        }
        return false;
    }

    /**
     * @return boolean successful or not
     */
    public function updateCustomFieldname(string $table, string $where_fieldname, int $where_id, array $fields_to_values)
    {
        // generate mysql query statement with table and fields with placeholders for values
        $stmt_query = "UPDATE `" . $table . "` SET ";
        $stmt_values = [];

        if (count($fields_to_values) > 0) {
            $i = 0;
            foreach ($fields_to_values as $field => $value) {
                $stmt_query .= "`" . $field . "` = ?";
                $stmt_values[] = $value;

                if ($i < (count($fields_to_values) - 1)) {
                    $stmt_query .= ", ";
                }
                $i++;
            }
        } else {
            return false;
        }

        // add condition with placeholder
        if ($where_id > 0) {
            $stmt_query .= " WHERE `" . $where_fieldname . "` = ?";
            $stmt_values[] = (int) $where_id;
        } else {
            return false;
        }

        // replace placeholders with values
        if ($stmt = $this->db->prepare($stmt_query)) {
            $types = "";

            for ($i = 1; $i <= count($stmt_values); $i++) {
                $types .= "s";
            }

            array_unshift($stmt_values, $types);

            $ref_values = [];
            foreach ($stmt_values as $key => $value) {
                $ref_values[$key] = &$stmt_values[$key];
            }

            call_user_func_array(array($stmt, "bind_param"), $ref_values);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }

            $stmt->close();
        }
        return false;
    }

    private function query($query)
    {
        return $this->db->query($query);
    }
}
