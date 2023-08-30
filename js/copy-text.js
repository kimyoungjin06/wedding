function CopyText(_id) {
    buttonId = document.getElementById(_id);
    textToChangeBackTo = buttonId.text;
    navigator.clipboard.writeText(textToChangeBackTo);

    tc = textToChangeBackTo.replace(/\ /g, '\n');

    buttonId.textContent = "복사 완료";
    setTimeout(function() { document.getElementById(_id).textContent = tc }, 500);
  
    // Alert the copied text
    // alert("계좌 복사 완료: " + text);
    // console.log(textToChangeBackTo, '!!!')
  }