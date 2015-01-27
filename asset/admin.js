var Admin = {
  login : function(){
    var nome = $('#nome').val();
    var password = $("#password").val();

    $.post('admin.php?p=login',
    {
      username : nome,
      password : password,
    }, function (data){
      if (data == 'ok'){
        location.href = './admin.php?p=list';
      }
      else {
        alert('Problemi nel login');
        console.log(data);
        //location.href = './admin.php?p=login';
      }
    });
  },

  updateUser : function(){
    var password = $("#password").val();
    var email = $("#email").val();
    var nome_cognome = $("#nome_cognome").val();
    var eta = $("#eta").val();
    var citta = $("#citta").val();
    var cittanatale = $("#cittanatale").val();
    var hobbies = $("#hobbies").val();
    var studio = $("#studio").val();
    var id = $('#id').val();

    $.post( "?p=modifica&id="+id,
    {
      password : password,
      email : email,
      nome_cognome: nome_cognome,
      eta : eta,
      citta : citta,
      cittanatale : cittanatale,
      hobbies : hobbies,
      studio : studio
    },
    function (data) {
      if(data == "ok"){
        alert('Profilo aggiornato con successo');
      }
      else {
        alert("Problemi con l'update");
        console.log(data);
      }
    });
  },

  elimina : function(id) {
    if (!confirm('Sicuro di volere eliminare l\'utente?')) return;

    $.post( "?p=elimina",
    { id : id },
    function (data) {
      if(data == "ok"){
        alert('Profilo rimosso con successo');
        location.reload();
      }
      else {
        alert("Problemi con la rimozione del profilo");
        console.log(data);
      }
    });
  },

  eliminaNotizia: function(id) {
    if (!confirm('Sicuro di volere eliminare la notizia?')) return;

    $.post( "?p=notizie",
    { id : id },
    function (data) {
      if(data == "ok"){
        window.location.reload();
      }
      else {
        alert("Problemi con la rimozione della notizia");
        console.log(data);
      }
    });

  }
}




//wrapper
function updateUser(){
  Admin.updateUser();
}

//html5 file
function fileSelected() {
  var file = document.getElementById('fileToUpload').files[0];

  if (file) {
    var fileSize = 0;

    if (file.size > 1024 * 1024)
      fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
    else
      fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

  }
}

function uploadFile() {
  var fd = new FormData();
  fd.append("fileToUpload", document.getElementById('fileToUpload').files[0]);
  var id = $('#id').val();
  //upload the file
  var xhr = new XMLHttpRequest();
  xhr.addEventListener("load", uploadComplete, false);
  xhr.addEventListener("error", uploadFailed, false);
  xhr.addEventListener("abort", uploadCanceled, false);
  xhr.open("POST", "?p=asyncupload&id="+id);
  xhr.send(fd);

}

function uploadComplete(evt) {
  /* This event is raised when the server send back a response */
  document.getElementById('immagine').innerHTML = evt.target.responseText;
}

function uploadFailed(evt) {
  alert("There was an error attempting to upload the file.");
}

function uploadCanceled(evt) {
  alert("The upload has been canceled by the user or the browser dropped the connection.");
}
// fine upload del file
