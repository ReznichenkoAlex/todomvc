$(function ($) {
	'use strict';

	const App = {

		run: async function () {
			this.todoItems = await this.getTodos();
			this.todoTemplate = Handlebars.compile($('#todo-template').html());
			this.footerTemplate = Handlebars.compile($('#footer-template').html());
			$('.todo-list').html(this.todoTemplate(this.todoItems));
			const todoCount = this.todoItems.length;
			const activeTodoCount = this.getActiveTodos().length;
			$('.footer').html(this.footerTemplate({
				activeTodoCount: activeTodoCount,
				completedTodos: todoCount - activeTodoCount
			}));
			$('.todo-list').on('click', '.toggle', function (e) {
				const uuid = $(e.target).closest('li').data().id;
				const index = this.getIndex(uuid);
				this.todoItems[index].isCompleted = !this.todoItems[index].isCompleted;
				localStorage.setItem('data', JSON.stringify(this.todoItems));
				$.ajax({
						url: '/api/patch',
						method: 'PATCH',
						data: JSON.stringify(this.todoItems[index]),
						contentType: 'application/json',
						processData: false,
					}
				)
				$('.todo-list').html(this.todoTemplate(this.todoItems));
				const todoCount = this.todoItems.length;
				const activeTodoCount = this.getActiveTodos().length;
				$('.footer').html(this.footerTemplate({
					activeTodoCount: activeTodoCount,
					completedTodos: todoCount - activeTodoCount
				}));
			}.bind(this))
		},
		getActiveTodos() {
			return this.todoItems.filter(todo => {
				return !todo.isCompleted;
			});
		},
		async getTodos() {
			let data = JSON.parse(localStorage.getItem('data'));
			if (!data) {
				const res = await $.getJSON('/api/get');
				data = res.data;
				localStorage.setItem('data', JSON.stringify(data));
			}
			return data;
		},
		getIndex(uuid) {
			let todos = this.todoItems
			let i = todos.length;

			while (i--) {
				if (todos[i].uuid === uuid) {
					return i;
				}
			}
		}
	}

	App.run();
});
