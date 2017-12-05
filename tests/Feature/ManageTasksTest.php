<?php

namespace Tests\Feature;

use App\Task;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_a_task()
    {
        // User mengunjungi halaman daftar task
        $this->visit('/tasks');

        // Isi form 'name' dan 'description' kemudian submit
        $this->submitForm('Create Task', [
            'name' => 'My First Task',
            'description' => 'This is my first task on my new job',
        ]);

        // Lihat record tersimpan ke database
        $this->seeInDatabase('tasks', [
            'name' => 'My First Task',
            'description' => 'This is my first task on my new job',
            'is_done' => 0,
        ]);

        // Redirect ke halaman daftar task
        $this->seePageIs('/tasks');

        // Tampil hasil task yang telah diinput
        $this->see('My First Task');
        $this->see('This is my first task on my new job');
    }

    /** @test */
    public function task_entry_must_pass_validation()
    {
        // submit form untuk membuat task baru
        // dengan field name description kosong
        $this->post('/tasks', [
            'name' => '',
            'description' => ''
        ]);

        // check pada session apakah ada error untuk field nama dan description
        $this->assertSessionHasErrors(['name', 'description']);
    }

    /** @test */
    public function user_can_browse_tasks_index_page()
    {
        // Generate 3 record task pada table 'tasks'
        $tasks = factory(Task::class, 3)->create();

        // User membuka halaman daftar task.
        $this->visit('/tasks');

        // User melihat ketiga task tampil pada halaman
        $this->see($tasks[0]->name);
        $this->see($tasks[1]->name);
        $this->see($tasks[2]->name);

        // User melihat link untuk edit task pada masing-masing item
        // <a href="/tasks?action=edit&id=1" id="edit_task_1">edit</a>
        $this->seeElement('a', [
            'id' => 'edit_task_'.$tasks[0]->id,
            'href' => url('tasks?action=edit&id='.$tasks[0]->id)
        ]);

        $this->seeElement('a', [
            'id' => 'edit_task_'.$tasks[1]->id,
            'href' => url('tasks?action=edit&id='.$tasks[1]->id)
        ]);

        $this->seeElement('a', [
            'id' => 'edit_task_'.$tasks[2]->id,
            'href' => url('tasks?action=edit&id='.$tasks[2]->id)
        ]);
    }

    /** @test */
    public function user_can_edit_an_existing_task()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function user_can_delete_an_existing_task()
    {
        $this->assertTrue(true);
    }
}
