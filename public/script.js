function toggleLogout(){
    var logout = document.getElementById('logout')

    if(logout.style.display === "" || logout.style.display === "none"){
        logout.style.display = "block"
    } else {
        logout.style.display = "none"
    }
}



function populatePayrollFields(selectElement) {
    var companyId = selectElement.value;
    axios.post('get_employee.php', new URLSearchParams({
        company_id: companyId
    }))
    .then(function (response) {
        // Check if there is an error in the response
        if (response.data.error) {
            console.error('Error:', response.data.error);
            document.getElementById('name').value = ''; 
            document.getElementById('rate_read').value = ''; 
        } else {
            // Set the value of the 'name' field to the employee's full name
            var fullName = response.data.first_name + ' ' + response.data.last_name;
            document.getElementById('name').value = fullName;
            if (document.getElementById('rate_read') && document.getElementById('allowance_read')){
                var total_rate = response.data.rate;
                var total_allowance = response.data.allowance;
                document.getElementById('rate_read').value = total_rate;
                document.getElementById('allowance_read').value = total_allowance;
            }
            
        }
        
    })
    .catch(function (error) {
        console.error('There was an error!', error);
        document.getElementById('name').value = ''; // Clear the field if there is an error
    });
}

function compute(rate_hour, allowance, total_hours, holidays, deduction) {
    var total_days = total_hours / 8;
    var total_allowance = total_days * allowance;
    var gross_pay = 0;
    var net_pay = 0;  

    if (holidays >= 1) {
      var double_pay = rate_hour * 2;
      var non_holiday_hours = total_hours - (holidays * 8);
      var normal_pay = (rate_hour * non_holiday_hours);
      var double_pay_hours = holidays * 8;
      var double_pay_amount = double_pay_hours * double_pay;
      gross_pay = normal_pay + double_pay_amount + total_allowance;
    } else {
        gross_pay = (rate_hour * total_hours) + total_allowance;
    }

    var sss = gross_pay * 0.06; // 6% of gross_pay
    var sssLoan = gross_pay * 0.02; // 2% of gross_pay
    var pagibigFund = gross_pay * 0.03; // 3% of gross_pay
    var pagibigLoan = gross_pay * 0.02; // 2% of gross_pay
    var philhealth = gross_pay * 0.07; // 7% of gross_pay
    
    var lates_deduction = gross_pay * (deduction / 100);

    var totalDeduction = sss + sssLoan + pagibigFund + pagibigLoan + philhealth - lates_deduction;
    
    net_pay = gross_pay - totalDeduction;
    return {
        "gross_pay": gross_pay,
        "net_pay": net_pay
    };
  }

function fetchAttendanceRecords() {
    const employeeId = document.getElementById('c_id_select').value;
    const startDate = document.getElementById('pay_start').value;
    const endDate = document.getElementById('pay_end').value;

    overtime = document.getElementById('ot');

    axios.post('get_attendance.php', new URLSearchParams({
        employee_id: employeeId,
        start_date: startDate,
        end_date: endDate
    }))
    .then(response => {
        if (response.data.error) {
            document.getElementById('ot_read').value = "";
        } else {
            totalHours = response.data.total_hours;

            const allowance = document.getElementById('allowance_read').value;
            const rate_hour = document.getElementById('rate_read').value;

            const holidays = response.data.holidays;
            const totalDays = totalHours / 8;

            const data = compute(rate_hour, allowance, totalHours, holidays, response.data.lates);

            document.getElementById('ot_read').value = totalHours;
            document.getElementById('days_read').value = totalDays;
            document.getElementById('holidays_read').value = holidays;
            document.getElementById('gross_read').value = data.gross_pay;
            document.getElementById('gross').value = data.gross_pay;
            document.getElementById('net_pay').value = data.net_pay;
            document.getElementById('net_pay_read').value = data.net_pay;
            document.getElementById('deduction').value = response.data.lates;

        }
    })
    .catch((error) => {
        // Handle any errors
        console.error('Error:', error);
        document.getElementById('ot_read').value = "";
    });
}