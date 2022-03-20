$(function ($) {
	'use strict';

	const App = {
		todoItems: [],

		run: async function () {

			const res = await $.getJSON('/api/get');
			this.todoItems = res.data;

		}
	}

	App.run();
});
