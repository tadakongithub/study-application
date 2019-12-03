$( document ).ready(function() {
    $('#done').on('click', function(){
        $('#study_submit').show("slow");
        $('#startStop').prop('disabled',true).css({
            backgroundColor: "#8f8f8f",
            color: "#fff",
            border: "none",
            cursor: "default"
        }); 
        $('#display').css({
            color: "#ff9900",
            "-webkit-text-fill-color": "#ff9900", 
            "-webkit-text-stroke-width": "1px",
            "-webkit-text-stroke-color": "#fff",
            "text-shadow": "2px 2px 2px #808080"
        });
    });
    
});