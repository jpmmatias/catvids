const postComment=(button,postedBy,videoId,responseTo,containerClass)=>{
    let textarea =$(button).siblings('textarea');
    let commentText = textarea.val();
    textarea.val('');

    if (commentText) {
        $.post("ajax/postComment.php",{commentText:commentText,postedBy:postedBy,videoId:videoId,responseTo:responseTo}).done((comment)=>{
            if (!responseTo) {
                $("."+containerClass).prepend(comment)
            } else{
                $(button).parent().siblings("."+containerClass).eq(0).append(comment)
            }
          
        })
    } else{
        alert("Você precisar escrever algo antes de comentar")
    }
}

const toggleReply=(btn)=>{
    let parent = $(btn).closest(".itemContainer");
    let commentForm = parent.find('.commentForm').first();
    commentForm.toggleClass("hidden");
}

const likeComment=(btn,commentId, videoId)=>{
    $.post("ajax/likeComment.php",{commentId,videoId}).done(res=>{

        let likeBtn = $(btn);
        let dislikeBtn = $(btn).siblings(".dislikeButton")

        likeBtn.addClass("active")
        dislikeBtn.removeClass("active")
        let likeCount= $(btn).siblings(".likeCount");
        updateLikesComment(likeCount,res)
        
        if (res<0) {
            likeBtn.removeClass("active")
            likeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-up.png")
        }else{
            likeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-up-active.png")
        }
        dislikeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-down.png")
    }
    )
}

const dislikeComment=(btn,commentId, videoId)=>{
    $.post("ajax/dislikeComment.php",{commentId,videoId}).done(res=>{

        let dislikeBtn = $(btn);
        let likeBtn = $(btn).siblings(".likeBtn")

        dislikeBtn.addClass("active")
        likeBtn.removeClass("active")
        let likeCount= $(btn).siblings(".likeCount");
        updateLikesComment(likeCount,res);

        
        if (res>0) {
            dislikeBtn.removeClass("active")
            dislikeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-down.png")
        }else{
            dislikeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-down-active.png")
        }
        likeBtn.find("img:first").attr("src","assets/imgs/icons/thumb-up.png")
    }
    )
}

const updateLikesComment=(el,num)=>{
    let likes = el.text() || 0;
    el.text(parseInt(likes)+parseInt(num));
}

const getReplies=(commentId,btn,videoId)=>{
    $.post("ajax/getCommentReplies.php",{commentId,videoId}).done(comments=>{
        let replies = $('<div>').addClass("repliesSection");
        replies.append(comments);
        $(btn).replaceWith(replies);
    })
}



