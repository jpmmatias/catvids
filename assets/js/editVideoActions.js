const setNewThumbnail=(thumbId,videoId,el)=>{
    $.post("ajax/updateThumbnail.php",{videoId:videoId,thumbId:thumbId}).done((res)=>{
        let item= $(el);
        let classItem= item.attr("class");

        $("." + classItem).removeClass("selected");

        item.addClass("selected");
        alert("Thumbnail atualizada!");
    })
}