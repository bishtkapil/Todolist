<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    {{-- <div class="container mt-5">
        <h1>Task Manager</h1>
        <input type="text" id="task-title" class="form-control" placeholder="Enter task">
        <button id="add-task" class="btn btn-primary mt-2">Add Task</button>
        <button id="show-all" class="btn btn-secondary mt-2">Show All Tasks</button>
        <ul id="tasks" class="list-group mt-3"></ul>
    </div> --}}

       <div class="container mt-5">
        <h1>To Do List App</h1>
        <input type="text" id="taskinput" class="form-control" placeholder="Enter task">
        <button id="addtaskbutton" class="btn btn-primary mt-2">Add Task</button>
        <button id="show-all" class="btn btn-secondary mt-2">Show All Tasks</button>
        <ul id="task_list" class="list-group mt-3"></ul>
    </div>

</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
   $(document).ready(function(){

$('#addtaskbutton').click(function(){
    var task_title = $('#taskinput').val();
    if(task_title !== ''){
        $.ajax({
            url: '/tasks',
            method: 'POST',
            data: { title: task_title,
                    _token: "{{ csrf_token() }}"
             },
            success: function(response) {
                console.log(response);
                $('#task_list').append(
                    `<li class="list-group-item" data-id="${response.id}">
                        <input type="checkbox" class="mark-complete"> ${response.title}
                        <button class="delete-task btn btn-danger btn-sm float-right">Delete</button>
                    </li>`
                );
                $('#taskinput').val('');
            },
            error: function(xhr, status, error) {
                // Handle error response
                if(xhr.responseJSON.errors && xhr.responseJSON.errors.title) {
                    alert(xhr.responseJSON.errors.title[0]);
                } else {
                    console.error(xhr.responseText);
                }
            }
        });
    }
});


        $('#task_list').on('change', '.mark-complete', function() {
            const row = $(this).closest('tr');
            const task_id = row.data('id');
            // console.log(task_id);
            const completed = $(this).is(':checked');
            // console.log(completed);
            $.ajax({
                url: `/tasks/${task_id}`,
                method: 'post',
                data: {
                    completed: completed,
                    _token: "{{ csrf_token() }}"

                }
            }).done(function() {
                if (completed) {
                    row.css('text-decoration', 'line-through');
                } else {
                    row.css('text-decoration', 'none');
                }
            });
        });


//delete

$('#task_list').on('click', '.delete-task', function() {
    if (confirm('Are you sure you want to delete this task?')) {
        const taskElement = $(this).closest('tr');
        console.log(taskElement);
        const taskId = taskElement.data('id');
        console.log(taskId);

        $.ajax({
            url: `/tasks/${taskId}`,
            method: 'DELETE',
            data: { taskId: taskId,
                    _token: "{{ csrf_token() }}"
             },
            success: function(response) {
                taskElement.remove();
            }
        });
    }
});

//show data

$('#show-all').click(function() {
$.ajax({
    url: '/show_data',
    method: 'GET',
    dataType: 'json',
    success: function(tasks) {
        $('#task_list').empty(); 
    
            $('#task_list').append(`
                <table class="table">
                    <thead>
                        <tr>
                        <th>id</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="task_table_body"></tbody>
                </table>
            `);
            tasks.forEach(task => {
                const completed = task.completed ? 'checked' : '';
                const status = task.completed == 0 ? 'Done' : 'Pending';
                // console.log(status);
                $('#task_table_body').append(`
                    <tr data-id="${task.id}">
                    <td>${task.id}</td>
                        <td><input type="checkbox" class="mark-complete" ${completed}> ${task.title}</td>
                        <td>${status}</td>
                        <td>
                        
                            <button class="delete-task btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                `);
        if (task.completed) {
            $(`li[data-id=${task.id}]`).css('text-decoration', 'line-through');
        }
     });
    },
        error: function(xhr, status, error) {
        console.error(xhr.responseText);
    }
 });

});


});


</script>
