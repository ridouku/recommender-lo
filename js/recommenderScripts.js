function isAnswered() {//Check if the user doesn't select an answers in the profile test
        var radioGroups = {}
        for(i in (inputs = document.getElementsByTagName('input'))) {
          if(inputs[i].type === 'radio') {
            radioGroups[inputs[i].name] = radioGroups[inputs[i].name] ? true : inputs[i].checked;
          }
        }
        for(i in radioGroups) {
          if(radioGroups[i] === false) return false; 
        }
        return true;
}
function addRating(obj,ol,id)  {//Adds a new rating to one OA with the current user
        var stol=ol;
        var stid=id;
        var strate=obj.value;
        var host="http://192.168.200.2:8000/updateRating/";
        var nurl=host.concat(stid,"/",stol,"/",strate);
        $.ajax({
        url : nurl, // the endpoint
        type : "GET", // http method
        data: {}, // data sent with the post request
        success : function(json) {
            console.log(json); // another sanity check
        },
        error : function(xhr,errmsg,err) {
          alert('Problemas con el servidor, tu solicitud no pudo ser resuelta');
        }
    });
};