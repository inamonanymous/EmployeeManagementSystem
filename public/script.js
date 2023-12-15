function toggleLogout(){
    var logout = document.getElementById('logout')

    if(logout.style.display === "" || logout.style.display === "none"){
        logout.style.display = "block"
    } else {
        logout.style.display = "none"
    }
}

function compute(){
    var rate = document.getElementById('rate');
    var days = document.getElementById('days');
    var gross_pay = document.getElementById('gross')

    var rateValue = rate.value;
    var workingDays = days.value;

    pay = rateValue * workingDays

    gross_pay.value = pay;
}