let modal = document.querySelector("#modalWindow");
let deletePrompt = document.querySelector("#deleteCheck");

function openDetails(target){
    console.log()
    modal.style.display = "flex";
    const eventId = target.querySelector("#eventID").innerHTML;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector("#eventDetails").innerHTML = this.responseText;
        }
    }
    xmlhttp.open("GET", "scripts/php/getEventDetails.php?id=" + eventId, true);
    xmlhttp.send();
}

function closeModal() {
  modal.style.display = "none";
}

function deleteEvent(){
  const eventId = document.querySelector("#eventDetailsId").innerHTML;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = async function() {
      if (this.readyState == 4 && this.status == 200) {
        var x = document.getElementById("snackbar");
        x.innerHTML = this.responseText;
        x.className = "show";
        modal.style.display = "none";
        deletePrompt.style.display = "none";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
        document.getElementById("events").classList.remove("visible");
        await new Promise(resolve => setTimeout(resolve, 500));
        getEvents();
      }
  }
  xmlhttp.open("GET", "scripts/php/deleteEvent.php?id=" + eventId, true);
  xmlhttp.send();
}

function getEvents(){
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          document.getElementById("events").innerHTML = this.responseText;
          document.getElementById("events").classList.add("visible");
          var heightnow=$("#events").height();
          var heightfull=$("#events").css({height:'auto'}).height();
          $("#events").css({height:heightnow}).animate({
              height: heightfull
          }, 500);
      }
  }
  let category = document.querySelector("#categorySelect").value;
  let importance = document.querySelector("#importanceSelect").value;
  let date = document.querySelector("#dateSelect").value;
  xmlhttp.open("GET", "scripts/php/getEvents.php?category=" + category + "&importance=" + importance + "&date=" + date, true);
  xmlhttp.send();
}

function promptDelete(){
  deletePrompt.style.display = "flex";
}

function cancelDelete(){
  deletePrompt.style.display = "none";
}

function editEvent(){
  const eventId = document.querySelector("#eventDetailsId").innerHTML;
  var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector("#eventDetails").innerHTML = this.responseText;
            setupUpdateSubmit();
        }
    }
    xmlhttp.open("GET", "scripts/php/getEventEditForm.php?id=" + eventId, true);
    xmlhttp.send();
}

function setupUpdateSubmit(){
  $("#editForm").submit(function(event) {
    event.preventDefault();
    if (request) {
        request.abort();
    }
    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();
    $inputs.prop("disabled", true);

    request = $.ajax({
        url: "scripts/php/updateEvent.php",
        type: "post",
        data: serializedData
    });

    let closePrompt = true;

    request.done(function (response, textStatus, jqXHR){
      if(response == "The date of the event can't be in the past!"){
        closePrompt = false;
      }
      $('#editForm')[0].reset();
      var xmlhttp = new XMLHttpRequest();
      var x = document.getElementById("snackbar");
        x.innerHTML = response;
        x.className = "show";
        if(closePrompt)
          closeModal();
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
        if(response == "The event was successfully updated!"){
          xmlhttp.onreadystatechange = async function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("events").classList.remove("visible");
              await new Promise(resolve => setTimeout(resolve, 500));
              document.getElementById("events").innerHTML = this.responseText;
              var heightnow=$("#events").height();
              var heightfull=$("#events").css({height:'auto'}).height();
              $("#events").css({height:heightnow}).animate({
                  height: heightfull
              }, 500);
              document.getElementById("events").classList.add("visible");
            }
        }
      }
      if(response == "The event was successfully updated!"){
        let category = document.querySelector("#categorySelect").value;
        let importance = document.querySelector("#importanceSelect").value;
        let date = document.querySelector("#dateSelect").value;
        xmlhttp.open("GET", "scripts/php/getEvents.php?category=" + category + "&importance=" + importance + "&date=" + date, true);
        xmlhttp.send();
      }
    });

    request.always(function () {
      $inputs.prop("disabled", false);
    });
  });
}