$(function(){
    alert("working");
    loadData();

});

function loadData(){
    $.ajax({
        url:'read.php',
        method:'get',
        success:function(response){
            console.log(response);
        }
    });
};