<?php
    $id = $_REQUEST["id"];

    require_once("db/eventTable.php");
    $obj = new EventTable("Event");
    $data = $obj->getEventById($id);

    $response = '';

    while ($row = $data->fetch()){
        function getCategorySelectValue($name, $category){
            if($category == $name)return "selected";
            else return "";
        }
        
    $response .= '<span class="close" onclick="closeModal()">&times;</span>
          <h2>Edit "' .$row["name"].'"</h2>
          <form id="editForm" action="scripts/php/updateEvent.php">
          <table id="eventDetailTable">
            <tr>
              <td>Name:</td>
              <td><input id="updateName" type="text" required name="name" value="' .$row["name"].'" maxlength="100"></td>
            </tr>
            <tr>
              <td>Date:</td>
              <td><input id="updateDate" type="datetime-local" required name="date" value="' .date("Y-m-d\TH:i", strtotime($row["date"])).'"></td>
            </tr>
            <tr>
              <td>Location:</td>
              <td><input type="text" required name="location" value="'.$row["location"].'"></td>
            </tr>
            <tr>
              <td>Category:</td>
              <td><select name="category">
              <option value="Work" '.getCategorySelectValue("Work", $row["category"]).'>Work</option>
              <option value="Education" '.getCategorySelectValue("Education", $row["category"]).'>Education</option>
              <option value="Family" '.getCategorySelectValue("Family", $row["category"]).'>Family</option>
              <option value="Food and drink" '.getCategorySelectValue("Food and drink", $row["category"]).'>Food and drink</option>
              <option value="Entertainment" '.getCategorySelectValue("Entertainment", $row["category"]).'>Entertainment</option>
              <option value="Leisure" '.getCategorySelectValue("Leisure", $row["category"]).'>Leisure</option>
              <option value="Other" '.getCategorySelectValue("Other", $row["category"]).'>Other</option>
            </select></td>
            </tr>
            <tr>
              <td>Importance:</td>
              <td><select name="importance">
              <option value="Low" '.getCategorySelectValue("Low", $row["importance"]).'>Low</option>
              <option value="Medium" '.getCategorySelectValue("Medium", $row["importance"]).'>Medium</option>
              <option value="High" '.getCategorySelectValue("High", $row["importance"]).'>High</option>
            </select></td>
            </tr>
            <tr class="lastRow">
              <td>Description:</td>
              <td><textarea id="editDescr" name="description">'.$row["description"].'</textarea></td>
            </tr>
          </table>
          <div id="eventDetailButtons">
            <input type="submit" id="updateBtn" value="Update event">
          </div>
          <input type="hidden" name="id" value="'. $row["event_id"] .'">
          </form>'
          ;
              }
    echo $response;
