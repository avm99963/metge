function reservar(dia, hora, posicio, idvisit, codename) {
  if (!working) {
    working = true;
    var id = dia+"-"+hora+"-"+posicio;
    var button_elements = document.getElementsByTagName("button");
    for (i = 0; i < button_elements.length; i++) {
      if (button_elements[i].disabled) {
        button_elements[i].setAttribute("data-disabled", "yes");
      }
      button_elements[i].disabled = true;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        console.log(xmlhttp.responseText);
        obj = JSON.parse(xmlhttp.responseText);
        if (obj.status == "ok") {
          document.getElementById(id).innerHTML = "<span style='vertical-align: middle;'>"+obj.text+"</span> <a href='cancelreservar.php?dia="+obj.dia+"&hora="+obj.hora+"&posicio="+obj.posicio+"'><i class='material-icons delete'>delete</i>";
          document.getElementById(id).className = "user mdl-color--green-200";
          for (i = 0; i < button_elements.length; i++) {
            if ((button_elements[i].getAttribute("data-codename") != codename && button_elements[i].getAttribute("data-disabled") != "yes") || isadmin === true) {
              button_elements[i].disabled = false;
            }
          }
          if (redirect === false) {
            document.querySelector('.mdl-js-snackbar').MaterialSnackbar.showSnackbar({message: "Se ha guardado "+(isadmin ? "el bloqueo" : "la reserva")+" correctamente."});
          }
        } else {
          console.error("Error '"+obj.status+"': \""+obj.statustxt+"\"");
          document.querySelector('.mdl-js-snackbar').MaterialSnackbar.showSnackbar({message: "Error '"+obj.status+"': \""+obj.statustxt+"\""});
        }
        if (redirect) {
          window.location = redirecturl;
        }
      }
      working = false;
    }
    xmlhttp.open("POST","ajax/reservar.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("dia="+dia+"&hora="+hora+"&posicio="+posicio+"&idvisit="+idvisit);
  } else {
    console.warn("Hey, I'm working. Let me finish before sending another request :-P");
  }
}
