const postComment=(button,postedBy,videoId,responseTo,containerClass)=>{
    let textarea =$(button).siblings('textarea');
    let commentText = textarea.val();
    textarea.val('');

    if (commentText) {
        
        $.post("ajax/postComment.php",{commentText:commentText,postedBy:postedBy,videoId:videoId,responseTo:responseTo}).done((comment)=>{
            console.log(comment);
            $("."+containerClass).prepend(comment)
        })
    } else{
        alert("Você precisar escrever algo antes de comentar")
    }
}