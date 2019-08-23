function loadModal(modalId) {
  // debugger
  var showModal = document.getElementById(modalId);
  showModal.style.display = "block";

}

function loadHtmlPage(id){
  if (id === "inputForStatus") {
    document.getElementById(id).innerHTML='<object type="text/html" width="1500" height="1500" data="../html/status-bar-input.html" ></object>';
  }
  else {
    document.getElementById(id).innerHTML='<object type="text/html" width="1500" height="1500" data="../html/create-account.html" ></object>';
  }
}
