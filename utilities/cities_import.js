#!/usr/bin/env node

"use strict";

const fs = require('fs');
const axios = require('axios');

const filename = '/Users/daniel/Projects/GitHub/3012978/estados-cidades2.json';

async function write_city(data) {
	let response = await axios.put('http://localhost:8000/city', data);
	if (response.data.data.code === 201) {
		console.log(response.data.data.data);
	}
	else {
		console.log("Error -> " + response.config.data);
	}
	/*
		.then(response => {
			if (response.data.data.code === 201) {
				console.log(response.data.data.data);
			}
		}).catch (error => {
			let not_writed = error.config.data;

			console.log('Error' + " -> " + not_writed);
		});
		*/
}

{
	let data = JSON.parse(fs.readFileSync(filename, 'utf-8'));
	let cities = data.cities;
	let kcities = Object.keys(cities);

	for (let kc = 0; kc < kcities.length; kc++) {
		let city_id = cities[kc].id;
		let city_state_id = cities[kc].state_id;
		let city_name = ( cities[kc].name || cities[kc].nome );

		let data = {
			'id': city_id,
			'name': city_name,
			'state_id': city_state_id
		};

		setTimeout(() => { write_city(data); }, 10000);
	};
}
