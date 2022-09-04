//
// Code adapted from https://artisansweb.net/drag-drop-file-upload-using-javascript-php/
//
var fileobj;
function upload_file(e) {
    e.preventDefault();
    fileobj = e.dataTransfer.files[0];
    ajax_file_upload(fileobj);
}
  
function file_explorer() {
    document.getElementById('selectfile').click();
    document.getElementById('selectfile').onchange = function() {
        fileobj = document.getElementById('selectfile').files[0];
        ajax_file_upload(fileobj);
    };
}

const q = x => document.querySelector(x)
  
function ajax_file_upload(file_obj) {
    const name = q("#drop_file_name").value
    if(file_obj != undefined) {
        var form_data = new FormData();                  
        form_data.append('filename',name);
        form_data.append('file', file_obj);
        console.log("uploading");
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "ajax.php", true);
        xhttp.onload = function(event) {
            console.log({response:this.responseText})
            oOutput = document.querySelector('.img-content');
            if (xhttp.status == 200) {
                if( this.responseText.match(/^images/) ) {
                  const fn = this.responseText
                  const fnx = fn.split("?")[0]
                  oOutput.innerHTML = `Uploaded to: ${fnx}<br/>\n<img src='../${fn}' alt='${fn}' />`
                } else {
                  oOutput.innerHTML = `Error: ${this.responseText}`
                }
            } else {
                oOutput.innerHTML = "Error " + xhttp.status + " occurred when trying to upload your file.";
            }
            q("#drop_file_name").focus()
        }
 
        xhttp.send(form_data);
    }
}
