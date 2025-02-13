function confirmDelete(postId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        window.location.href = '/post/delete/' + postId;
    }
}
