@extends('layouts.app')
@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="post-view">
    
    <section class="image">
        <img src="{{ asset($post->getPostPicture()) }}" alt="Post Image">
    </section>

    <section class="image-info">
        
        <section class="user">
            <div class="username-pic">
                <section class="profile-picture">
                    <img src="{{ $post->user->getProfilePicture()  }}" alt="Profile Picture">
                </section>
                <a href="{{ route('users.show', $post->user->id) }}" > {{ $post->user->username }} </a>
            </div>

            @if ($post->user->id == auth()->id())
                <a href=" {{ route('posts.edit', $post->id) }} " class="edit-post">
                    <span>Edit post</span>
                    <i class="fa-regular fa-pen-to-square"></i>
                </a>
            @endif
        </section>

        <section class="content">
            <span class="description"> {{ $post->description }} </span>
        </section>

        <section class="d-i">
            @include('pages.post.likes')
            <p class="date">Created on: {{ $post->creation_date }}</p>
        </section>
                
        <section class="comments-section">
            @foreach ($post->comments->where('previous_comment_id', null) as $comment)
                <div class="comment" id="comment-{{ $comment->id }}">
                    <section class="user2">
                        <div class="username-pic">
                            <section class="profile-picture2">
                                <img src="{{ $comment->user->getProfilePicture()  }}" alt="Profile Picture">
                            </section>
                            <a href="{{ route('users.show', $comment->user->id) }}" > {{ $comment->user->username }} </a>
                        </div>
                        @if ($comment->user->id == auth()->id())
                            <button type="button" class="edit-comment" pdata-id="{{ $post->id }}" cdata-id="{{ $comment->id }}">
                                <span>Edit comment</span>
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        @endif
                    </section>
                    <p id="comment-content-{{ $comment->id }}"> {{ $comment->content }} </p>
                    <section class="d-i">
                        @include('pages.post.comments.likes')
                        <p class="date">Created on: {{ $comment->date }}</p>
                    </section>
                    @if ($comment->childComments)
                        <div class="replies">
                        @foreach ($comment->childComments as $reply)
                            <div class="comment reply" id="comment-{{ $reply->id }}">
                                
                                <section class="user2">
                                    <div class="username-pic">
                                        <section class="profile-picture2">
                                            <img src="{{ $reply->user->getProfilePicture()  }}" alt="Profile Picture">
                                        </section>
                                        <a href="{{ route('users.show', $reply->user->id) }}" > {{ $reply->user->username }} </a>
                                    </div>
                                    @if ($comment->user->id == auth()->id())
                                        <button type="button" class="edit-comment" pdata-id="{{ $post->id }}" cdata-id="{{ $reply->id }}">
                                            <span>Edit comment</span>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                    @endif
                                </section>
                                
                                <p id="comment-content-{{ $reply->id }}">{{ $reply->content }}</p>
                                
                                @include('pages.post.comments.child-likes', ['post' => $post, 'comment' => $reply])
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach
        </section>

        <section class="comment-input">
            <form action="{{ route('posts.comments.store', ['id' => $post->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="reply-to" name="previous_comment_id" value="">
                <textarea id="comment-box" name="content" placeholder="Add new comment..." rows="1" required></textarea>
                <button type="submit"> Post </button>    
            </form>
        </section>

    </section>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Event listener for Edit Comment button
    document.querySelectorAll('.edit-comment').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('cdata-id');
            const postId = this.getAttribute('pdata-id');
            const commentContent = document.getElementById(`comment-content-${commentId}`);
            
            // Avoid re-creating the textarea
            if (commentContent.querySelector('textarea')) return;

            // Get current text
            const currentText = commentContent.textContent.trim();

            // Create a container div for textarea and buttons
            const editContainer = document.createElement('div');
            editContainer.classList.add('edit-container');

            // Create a textarea element dynamically
            const textarea = document.createElement('textarea');
            textarea.value = currentText;
            textarea.rows = 2;
            textarea.classList.add('new-comment');

            // Replace comment content with textarea
            commentContent.innerHTML = '';
            commentContent.appendChild(textarea);

            // Create a container div for buttons
            const buttonContainer = document.createElement('div');
            buttonContainer.classList.add('edit-button-container');

            // Add Save, Cancel and Delete buttons
            const saveButton = document.createElement('button');
            saveButton.textContent = 'Save';
            saveButton.classList.add('btn', 'btn-success', 'button-sc');

            const cancelButton = document.createElement('button');
            cancelButton.textContent = 'Cancel';
            cancelButton.classList.add('btn', 'btn-danger', 'button-sc');

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Remove';
            deleteButton.classList.add('btn', 'btn-danger', 'button-delete');

            // Append textarea and buttons to the container
            editContainer.appendChild(textarea);
            buttonContainer.appendChild(saveButton);
            buttonContainer.appendChild(cancelButton);
            buttonContainer.appendChild(deleteButton);
            editContainer.appendChild(buttonContainer);

            // Clear current content and append the container
            commentContent.innerHTML = '';
            commentContent.appendChild(editContainer);

            // Event Listener for Save
            saveButton.addEventListener('click', function () {
                const updatedText = textarea.value.trim();

                fetch(`/posts/${postId}/comments/${commentId}/update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ content: updatedText })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        commentContent.innerHTML = updatedText;
                    } else {
                        alert('Error updating comment!');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // Event Listener for Cancel
            cancelButton.addEventListener('click', function () {
                commentContent.innerHTML = currentText;
            });

            // Event Listener for Delete
            deleteButton.addEventListener('click', function () {
                if (confirm('Are you sure you want to delete this comment?')) {
                    fetch(`/posts/${postId}/comments/${commentId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`comment-${commentId}`).remove();
                        } else {
                            alert('Error deleting comment!');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
});
</script>




