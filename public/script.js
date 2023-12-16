function toggleLogout(){
    var logout = document.getElementById('logout')

    if(logout.style.display === "" || logout.style.display === "none"){
        logout.style.display = "block"
    } else {
        logout.style.display = "none"
    }
}

function compute(){
    var hours = 8;
    var ot_pay = 90;

    var rate = document.getElementById('rate');
    var days = document.getElementById('days');
    var ot = document.getElementById('ot');
    var holidays = document.getElementById('holidays');
    var allowance = document.getElementById('allowance');

    var sss = document.getElementById('sss');
    var sssLoan = document.getElementById('sss_loan');
    var pagibigFund = document.getElementById('pagibig_fund');
    var pagibigLoan = document.getElementById('pagibig_loan');
    var philhealth = document.getElementById('philhealth');
    var deduction = document.getElementById('deduction');

    var net_pay = document.getElementById('net_pay');
    var gross_pay = document.getElementById('gross');

    var rateValue = parseInt(rate.value);
    var workingDays = parseInt(days.value);
    var holidayValue = (holidays.value);
    var otValue = parseInt(ot.value);
    var allownaceValue = parseInt(allowance.value);

    var sssValue = parseInt(sss.value);
    var sssLoanValue = parseInt(sssLoan.value);
    var pagibigFundValue = parseInt(pagibigFund.value);
    var pagibigLoanValue = parseInt(pagibigLoan.value);
    var philhealthValue = parseInt(philhealth.value);
    var deductionValue = parseInt(deduction.value);

    pay = rateValue * workingDays;
    holidayPay = holidayValue * rateValue;
    otPay = otValue * ot_pay;

    totalDeduction = sssValue + sssLoanValue + pagibigFundValue + pagibigLoanValue + philhealthValue + deductionValue;

    totalSalary = allownaceValue + (holidayPay + pay + otPay);

    
    

    gross_pay.value = totalSalary;

    net_pay.value = totalSalary - totalDeduction;
}