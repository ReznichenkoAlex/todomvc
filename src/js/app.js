$(function ($) {
	'use strict';

	const App = {

		render: function () {
			$('.todo-list').html(this.todoTemplate(this.todoItems));

			const todoCount = this.todoItems.length;
			const activeTodoCount = this.getActiveTodos().length;

			$('.main').toggle(todoCount > 0);

			$('.footer').toggle(todoCount > 0).html(this.footerTemplate({
				activeTodoCount: activeTodoCount,
				completedTodos: todoCount - activeTodoCount
			}));

		},

		deleteTask: function (e) {
			const uuid = $(e.target).closest('li').data().id;
			const index = this.getIndex(uuid);
			this.todoItems.splice(index, 1);
			localStorage.setItem('data', JSON.stringify(this.todoItems));
			$.ajax({
					url: '/api/delete',
					method: 'DELETE',
					data: JSON.stringify({uuid: uuid}),
					contentType: 'application/json',
					processData: false,
				}
			)
			this.render();
		},

		run: async function () {
			this.todoItems = await this.getTodos();
			this.todoTemplate = Handlebars.compile($('#todo-template').html());
			this.footerTemplate = Handlebars.compile($('#footer-template').html());
			this.render();

			$('.new-todo').on()

			$('.todo-list')
				.on('click', '.toggle', this.togleTask.bind(this))
				.on('click', '.destroy', this.deleteTask.bind(this));

			$('.toggle-all').on('click', this.toggleAll.bind(this));
		},

		toggleAll: function (e) {
			let toggleBool = $(e.target).prop('checked');

			this.todoItems.forEach(todo => {
				todo.isCompleted = toggleBool;
				this.sendAjaxJson('api/patch', todo, 'PATCH');
			})
			localStorage.setItem('data', JSON.stringify(this.todoItems));
			this.render();
		},

		sendAjaxJson: function (url, data, method = 'GET') {
			$.ajax({
					url: url,
					method: method,
					data: JSON.stringify(data),
					contentType: 'application/json',
					processData: false,
				}
			)
		},

		togleTask: function (e) {
			const uuid = $(e.target).closest('li').data().id;
			const index = this.getIndex(uuid);
			this.todoItems[index].isCompleted = !this.todoItems[index].isCompleted;
			localStorage.setItem('data', JSON.stringify(this.todoItems));
			this.sendAjaxJson('api/patch', this.todoItems[index], 'PATCH');
			this.render();
		},

		getActiveTodos: function () {
			return this.todoItems.filter(todo => {
				return !todo.isCompleted;
			});
		},
		getTodos: async function () {
			let data = JSON.parse(localStorage.getItem('data'));
			if (!data || data.length === 0) {
				const res = await $.getJSON('/api/get');
				data = res.data;
				localStorage.setItem('data', JSON.stringify(data));
			}
			return data;
		},
		getIndex: function (uuid) {
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
