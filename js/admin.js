
var modal = document.getElementById('mainModal');

var btn = document.getElementById("mainBtn");

var span = document.getElementsByClassName("close")[0];

btn.addEventListener('click',buttons);

function buttons() {
  modal.style.display = "block";
}

span.addEventListener('click',spanning);
function spanning() {
  modal.style.display = "none";
}

window.addEventListener('click',windows);
function windows(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

function urlClick(url){
  window.send_to_editor("[3D model="+url+"]");
    modal.style.display = "none";
}

