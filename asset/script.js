
// pulisco tutto quello che potrebbe andare storto
(function (window, document, undefined) {

  // faccio il caching dei nodi
  changeButton = document.getElementById("update_btn");
  statusDiv = document.getElementById("status");
  statusInput = document.getElementById("newstatus");
  stato = "";
  newToken = "";
  rules = ["\|", "\+", "\-\-", "=" , "<" , ">" , "!=" , "(" , ")" , "%" , "*" ];

  // associo il segnale del click al mio slot
  changeButton.onclick = function(){
    // provo a cambiare il valore dello stato
    try {
      // se lo stato è vuoto lancio un'eccezione EmptyStatus
      if (statusInput.value === "") {
        throw "EmptyStatus";
      }
      stato = statusInput.value;
      statusInput.value = '';

      // effettuo l'escape dei caratteri
      rules.forEach(function(entry){
        if(stato.indexOf(entry) > -1){
          throw "IllegalCharacter";
        }
      });

      // lo stato deve essere di 140 caratteri
      stato = stato.substr(0,140);

      // uso una chiamata post asincrona per cambiare il messaggio

      var xhr = new XMLHttpRequest();
      xhr.open("POST", "./?a=stato", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("stato="+stato);
      xhr.onreadystatechange = function() {//Call a function when the state changes.
        if(xhr.readyState == 4 && xhr.status == 200) {
          if (xhr.responseText === 'ok') {
            statusDiv.innerHTML = stato;
          }
          else{
            statusDiv.innerHTML = "<b>Qualcosa è andato storto</b>";
          }
        }
        else{
          console.log("Qaulcosa è andato storto");
          console.log(xhr.responseText)
        }
      }
    }
    catch (e){
      // raccolgo l'eccezione
      if (e === "EmptyStatus") {
        alert("Lo stato è vuoto");
      }
      else if (e === "IllegalCharacter"){
        alert("Caratteri illegali nello stato");
      }
      else {
        alert("Qualcosa è andato storto");
      }
      // nel caso controllo l'eccezione anche
      // nella console
      console.log(e);
    }
  }


})(window, document);

function cambiaDiagramma(str){

  var mtitle = []
  mtitle['da'] = 'Numero accessi al giorno';
  mtitle['ma'] = 'Numero accessi al mese';
  mtitle['wa'] = 'Numero accessi alla settimana';
  mtitle['mp'] = 'Numero post al mese';
  mtitle['yp'] = 'Numero post all\'anno';
  mtitle['like'] = 'Numero dei like';

  google.load('visualization','1',
    {'packages':['corechart'],
    callback: function() {
      $.getJSON( "?a=statistiche_json&t="+str, function( obj ) {

        var arr = [['nome', 'contatore']];

        for (var i = 0; i < obj.length; ++i){
          arr.push([obj[i].nome, parseInt(obj[i].contatore)]);
        }

        // Create our data table out of JSON data loaded from server.
        var data = google.visualization.arrayToDataTable(arr);
          // Instantiate and draw our chart, passing in some options.
          var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
          chart.draw(data, {width: 640, height: 480, title: mtitle[str] });
        });}
      });
}

function cerca(){
  var parametro = $("#parametro").val();
  var ordinamento = $("select#ordinamento option:selected").val();

  $.post( "?a=search",
  {
    parametro : parametro,
    ordinamento : ordinamento
  },
  function (data) {
    $("#dyn_ricerca").html(data);
  });

}

function login(){
  var username = document.getElementById('nome').value;
  var password = document.getElementById('password').value;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "./?a=login", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("username="+username+"&password="+password);

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(xhr.readyState == 4 && xhr.status == 200) {
      if (xhr.responseText === 'ok') {
        document.location.href = './?a=home';
      }
      else{
        document.getElementById('error').innerHTML = "<b>Qualcosa è andato storto nel login</b>";
      }
    }
    else{
      document.getElementById('error').innerHTML = "<b>Qualcosa è andato storto nel login</b>";
      console.log(xhr.responseText);
    }
  }
}

function like(like, id, elem){
  var xhr = new XMLHttpRequest();
  var el = elem.parentElement.getElementsByClassName(like+'_n')[0];

  xhr.open("GET", "./?a="+like+"&id="+id, true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(null);

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(xhr.readyState == 4 && xhr.status == 200) {
      if (xhr.responseText === 'ok') {
        el.innerHTML = parseInt(el.innerHTML) +1;
      }
    }
    else{
      console.log(xhr.responseText);
    }
  }
}

function registerUser(){

  var username = $("#username").val();
  var password = $("#password").val();
  var email = $("#email").val();

  $.post( "?a=registrazione",
    {
      username : username,
      password : password,
      email : email
    },
    function (data) {
      if(data == "ok"){
        alert("Utente registrato con successo, ora puoi effettuare il login");
      }
      else {
        alert("Problemi con la registrazione");
        console.log(data);
      }
    });
}

function updateUser(){
  var password = $("#password").val();
  var email = $("#email").val();
  var nome_cognome = $("#nome_cognome").val();
  var eta = $("#eta").val();
  var citta = $("#citta").val();
  var cittanatale = $("#cittanatale").val();
  var hobbies = $("#hobbies").val();
  var studio = $("#studio").val();

  $.post( "?a=profilo",
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
      alert("cambiato con successo");
    }
    else {
      $("#replace_div").html = "Problemi con l'update";
      console.log(data);
    }
  });
}


function chiediAmicizia (id){
  $.get('?a=amicizie&id='+id,
    function (data){
      if(data == "ok"){
        location.reload();
      }
      else {
        $("#replace_div").html = "Problemi con la richiesta di amcizia";
        console.log(data);
      }
    });
}

function accettaAmicizia(id) {
  $.post('?a=amicizie',
  {
    id : id
  },
  function (data){
    if(data == "ok"){
      location.reload();
    }
    else {
      $("#replace_div").html = "Problemi con la richiesta di amcizia";
      console.log(data);
    }
  });
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

  //upload the file
  var xhr = new XMLHttpRequest();
  xhr.addEventListener("load", uploadComplete, false);
  xhr.addEventListener("error", uploadFailed, false);
  xhr.addEventListener("abort", uploadCanceled, false);
  xhr.open("POST", "./?a=asyncupload");
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
