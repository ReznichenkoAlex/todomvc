$(function ($) {
	'use strict';

	const App = {

		run: async function () {

			const res = await $.getJSON('/api/get');
			this.todoItems = res.data;
			this.todoTemplate = Handlebars.compile($('#todo-template').html());
			this.footerTemplate = Handlebars.compile($('#footer-template').html());

			$('.todo-list').html(this.todoTemplate(this.todoItems));
			const todoCount = this.todoItems.length;
			const activeTodoCount = this.getActiveTodos().length;
			$('.footer').html(this.footerTemplate({
				activeTodoCount: activeTodoCount,
				completedTodos: todoCount - activeTodoCount
			}));

		},
		getActiveTodos() {
			return this.todoItems.filter(todo => {
				return !todo.isCompleted;
			});
		}
	}

	App.run();
});
