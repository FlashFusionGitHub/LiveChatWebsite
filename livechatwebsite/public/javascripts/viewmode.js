function toggleViewMode() {
    var element = document.body;
 
     if(getCookie("viewMode") == "dark") {

         element.classList = "";
         document.cookie = "viewMode=light;path=/";
     }
     else {
         element.classList = "dark-mode";
         document.cookie = "viewMode=dark;path=/";
     }
 
    document.getElementById("viewMode").name === "sunny-outline" ? document.getElementById("viewMode").name = "moon-outline" : document.getElementById("viewMode").name = "sunny-outline";
 }

function loadViewMode() {
    var element = document.body;

    if(getCookie("viewMode") == "dark") {
        element.classList = "dark-mode";
        document.getElementById("viewMode").name = "sunny-outline";
    }
    else {
        element.classList = "";
        document.getElementById("viewMode").name = "moon-outline";
    }
}

$(document).ready(function() {
    toogleHeaderBtns();
    loadViewMode();
});