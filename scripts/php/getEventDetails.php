<?php
    $id = $_REQUEST["id"];

    require_once("db/eventTable.php");
    $obj = new EventTable("event");
    $data = $obj->getEventById($id);

    $response = '';

    while ($row = $data->fetch()){
    $response .= '<span class="close" onclick="closeModal()">&times;</span>
          <h2>' .$row["name"].'</h2>
          <table id="eventDetailTable">
            <tr>
              <td>Date:</td>
              <td>' .date("d.m.Y. G:i", strtotime($row["date"])).'</td>
            </tr>
            <tr>
              <td>Location:</td>
              <td>'.$row["location"].'</td>
            </tr>
            <tr>
              <td>Category:</td>
              <td>'.$row["category"].'</td>
            </tr>
            <tr>
              <td>Importance:</td>
              <td>'.$row["importance"].'</td>
            </tr>
            <tr>
              <td>Description:</td>
              <td>'.$row["description"].'</td>
            </tr>
          </table>
          <div class="mapouter">
            <div class="gmap_canvas">
              <iframe id="gmap_canvas" src="https://maps.google.com/maps?q='.str_replace(" ", "%20", $row["location"]).'&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>
          </div>
          <div id="eventDetailButtons">
            <button type="button" id="editBtn" onclick="editEvent()">Edit event</button>
            <button type="button" id="deleteBtn" onclick="promptDelete()">Delete event</button>
          </div>
          <p style="display:none;" id="eventDetailsId">'. $row["event_id"] .'</p>';
              }
    echo $response;
