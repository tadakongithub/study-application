//Define time vars to hold time values
let seconds = 0;
let minutes = 0;
let hours = 0;

let displaySeconds;
let displayMinutes;
let displayHours;

let interval;

let status = "stopped";


//Stopwatch function (logic to determine when to increment value and tc)
function stopWatch(){

    seconds++;

    //logic to increment next value
    if(seconds / 60 === 1){
        seconds = 0;
        minutes++;

        if(minutes / 60 === 1){
            minutes = 0;
            hours++;
        }
    }

    if(seconds < 10){
        displaySeconds = '0' + seconds;
    } else {
        displaySeconds = seconds;
    }

    if(minutes < 10){
        displayMinutes = '0' + minutes;
    } else {
        displayMinutes = minutes;
    }

    if(hours < 10){
        displayHours = '0' + hours;
    } else {
        displayHours = hours;
    }

    //display time to user
    document.getElementById("display").innerHTML = displayHours + ':' + displayMinutes + ':' + displaySeconds;
}


function startStop(){
if(status == "stopped"){
    interval = window.setInterval(stopWatch, 1000);
    document.getElementById('startStop').innerHTML = 'Stop';
    status = 'started';
    document.getElementById('done').classList.add("disabled");
} else {
    window.clearInterval(interval);
    document.getElementById('startStop').innerHTML = 'Start';
    status = 'stopped';
    document.getElementById('done').classList.remove("disabled");
}
}

document.getElementById('startStop').addEventListener('click', (e) => {
    e.preventDefault();
    startStop();
})



document.getElementById('done').addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('subject_to_show_in_modal').innerHTML = `Subject: ${document.getElementById('study_subject').value}`;
    document.getElementById('time_to_show_in_modal').innerHTML = `Time: ${document.getElementById('display').innerHTML}`;
    document.getElementById('hidden').value = document.getElementById('display').innerHTML;
    document.getElementById('subject_in_modal').value = document.getElementById('study_subject').value;
    $('.ui.modal.confirm').modal('show');
    
})

/*
function reset(){
    window.clearInterval(interval);
    seconds = 0;
    minutes = 0;
    hours = 0;
    document.getElementById('display').innerHTML = '00:00:00';
    document.getElementById('startStop').innerHTML = 'Start';
    status = "stopped";
}
*/








