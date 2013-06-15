<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modelAbstract
 *
 * @author arkadij
 */
abstract class model_Abstract
{

    public function load($id, $field = NULL);

    public function save();

    public function delete($id = NULL);
}

?>
