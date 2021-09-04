let addEventBtn = document.querySelector("#addEventBtn");
let addEventPage = document.querySelector("#addEventPage");

addEventBtn.addEventListener("click", (event) => {
    event.preventDefault;
    addEventPage.classList.remove("noHeight");
    addEventPage.classList.add("fullHeight");
});

let closeCreateFormBtn = document.querySelector("#closeCreateForm");
closeCreateFormBtn.addEventListener("click", (event) => {
    event.preventDefault;
    addEventPage.classList.add("noHeight");
    addEventPage.classList.remove("fullHeight");
});

let request;

$("#addEventForm").submit(function(event) {
    event.preventDefault();
    if (request) {
        request.abort();
    }
    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();
    $inputs.prop("disabled", true);

    request = $.ajax({
        url: "scripts/php/addEventToDB.php",
        type: "post",
        data: serializedData
    });

    request.done(function (response, textStatus, jqXHR){
        processAdding(response);
    });

    request.always(function () {
        $inputs.prop("disabled", false);
    });
});

addEventBtn = document.querySelector("#addEvent");
addEventBtn.addEventListener("click", (event) => {
    event.preventDefault;
    
});

async function processAdding(response){
    $('#addEventForm')[0].reset();
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = async function() {
        if (this.readyState == 4 && this.status == 200) {
            var x = document.getElementById("snackbar");
            x.innerHTML = response;
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
            if(response == "The event was successfully added!"){
                document.getElementById("events").classList.remove("visible");
                await new Promise(resolve => setTimeout(resolve, 500));
                if(this.responseText == "<p id='noEventsFoundMsg'>No events found!</p>"){
                    document.getElementById("events").classList.remove("grid");
                }
                else{
                    document.getElementById("events").classList.add("grid");
                }
                document.getElementById("events").innerHTML = this.responseText;
                document.getElementById("events").classList.add("visible");
                var heightnow=$("#events").height();
                var heightfull=$("#events").css({height:'auto'}).height();
                $("#events").css({height:heightnow}).animate({
                    height: heightfull
                }, 500);
            }
        }
    }
    let category = document.querySelector("#categorySelect").value;
    let importance = document.querySelector("#importanceSelect").value;
    let date = document.querySelector("#dateSelect").value;
    xmlhttp.open("GET", "scripts/php/getEvents.php?category=" + category + "&importance=" + importance + "&date=" + date, true);
    xmlhttp.send();
}