function toogleHeaderBtns() {

    if(getCookie("JAR_") && getCookie("JAR")) {
        document.getElementById("loginBtn").innerHTML = 
        '<button class="btn btn-sm btn-outline-danger" type="button" id="btnLogin" value="LoginButton" onClick="location.href=\'/logout\'">Logout</button>';

        document.getElementById("accountBtn").innerHTML =
        '<button class="btn btn-sm btn-outline-success" type="button" id="btnAccount" value="CreateAccount" onClick="location.href=\'/account\'">My Account</button>';

        document.getElementById("search-bar").innerHTML = 
        '<div class="input-group input-group-sm" method="post">' +
            '<input type="text" class="search-bar form-control" name="friendName" placeholder="Find your friends">' +
            '<div class="input-group-append">' +
                '<button class="btn btn-secondary btn-sm btn-outline-primary" type="submit" name="btnSearch"><ion-icon name="search-outline" class="d-flex"></ion-icon></button>' +
            '</div>' +
        '</div>';
    }
    else {
        document.getElementById("loginBtn").innerHTML = 
        '<button class="btn btn-sm btn-outline-primary" type="button" id="btnLogin" value="LoginButton" onClick="location.href=\'/login\'">Login</button>';

        document.getElementById("accountBtn").innerHTML = 
        '<button class="btn btn-sm btn-outline-success" type="button" id="btnAccount" value="CreateAccount" onClick="location.href=\'/createAccount\'">Create Account</button>';
    }
}