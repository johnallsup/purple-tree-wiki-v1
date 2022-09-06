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
            const oOutput = document.querySelector('.img-content');
            const oMessage = document.querySelector('.img-upload-result .message')
            const oFilename = document.querySelector('.img-upload-result .filename')
            if (xhttp.status == 200) {
                if( this.responseText.match(/^images/) ) {
                  const fn = this.responseText
                  const fnx = fn.split("?")[0]
                  oFilename.innerHTML = `${fnx}`
                  oMessage.innerHTML = `Uploaded to:`
                  oOutput.innerHTML = `<img src='../${fn}' width="100%" alt='${fnx}' />`
                  oFilename.style.display = 'inline-block'
                } else {
                  oMessage.innerHTML = `Error: ${this.responseText}`
                  oOutput.innerHTML = ``
                  oFilename.innerHTML = ``
                  oFilename.style.display = 'none'
                }
            } else {
                oOutput.innerHTML = "Error " + xhttp.status + " occurred when trying to upload your file.";
            }
            q("#drop_file_name").blur()
        }
 
        xhttp.send(form_data);
    }
}

const do_copy = text => {
  const textArea = document.createElement("textarea")
  textArea.value = text
  textArea.style.position = "fixed"
  const miles = "-999999px"
  textArea.style.left = miles
  textArea.style.top = miles
  document.body.appendChild(textArea)
  textArea.focus()
  textArea.select()
  return new Promise((res, rej) => {
    // here the magic happens
    document.execCommand('copy') ? res() : rej()
    textArea.remove()
    search_input.focus()
  });
}
window.addEventListener("load", _ => {
  const oFilename = q(".img-upload-result .filename")
  oFilename.addEventListener("click", e => {
    e.preventDefault()
    const filename = oFilename.innerHTML
    do_copy(filename)
  })
  window.addEventListener("keydown", e => {
    const oInput = document.querySelector("input")
    if( oInput === document.activeElement ) return true
    if( e.ctrlKey || e.altKey || e.metaKey ) return true
    if( e.key === "Enter" ) {
      oInput.focus()
    }
    if( e.key.toLowerCase() === 'c' ) {
      const oFilename = q(".img-upload-result .filename")
      const filename = oFilename.innerHTML
      if( filename !== '' ) {
        do_copy(filename)
      }
    }
  })
})
