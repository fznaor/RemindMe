async function filterData(){
    document.getElementById("events").classList.remove("visible");
    await new Promise(resolve => setTimeout(resolve, 500));
    let category = document.querySelector("#categorySelect").value;
    let importance = document.querySelector("#importanceSelect").value;
    let date = document.querySelector("#dateSelect").value;

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
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
    xmlhttp.open("GET", "scripts/php/getEvents.php?category=" + category + "&importance=" + importance + "&date=" + date, true);
    xmlhttp.send();
}

window.addEventListener('resize', function(event) {
    var heightnow=$("#events").height();
    var heightfull=$("#events").css({height:'auto'}).height();
    $("#events").css({height:heightnow}).animate({
        height: heightfull
    }, 1);
}, true);