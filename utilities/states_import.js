#!/usr/bin/env node

"use strict";

const fs = require('fs');
const axios = require('axios');

const ec1_filename = '/Users/daniel/Projects/GitHub/3012978/estados-cidades.json';
const ec2_filename = '/Users/daniel/Projects/GitHub/3012978/estados-cidades2.json';

let siglas_por_nome = {};

{
	let data = JSON.parse(fs.readFileSync(ec1_filename, 'utf-8'));
	let states = data.estados;
	let kstates = Object.keys(states);
	kstates.forEach(ks => {
		siglas_por_nome[ states[ks].nome ] = states[ks].sigla;
	});
}

{
	let data = JSON.parse(fs.readFileSync(ec2_filename, 'utf-8'));
	let states = data.states;
	let kstates = Object.keys(states);
	kstates.forEach(ks => {
		let estado = states[ks];
		let sigla = siglas_por_nome[ estado ];

		console.log(ks + " -> " + sigla + " -> " + estado);

		let write = axios.put('http://localhost:8000/state', {
			'id': ks,
			'name': estado,
			'state_short': sigla,
			'country_id': 55
		}).then(response => {
			console.log(response);
		}).catch(error => {
			console.log(error);
		});;
	});
}
