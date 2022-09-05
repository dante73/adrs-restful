#!/usr/bin/perl -w
#
#    Procedimento para processar dados de Cidades e Estados do Brasil contidos nos arquivos : estados-cidades.js e estados-cidades2.json,
# disponibilizados no link : https://gist.github.com/letanure/3012978 e enviá-los através de POST para o sistema "adrs-restful", gravando
# assim seu conteúdo na base de dados deste sistema.

use strict;
use warnings;

use LWP;
use JSON qw(decode_json);

my $s = '{ "id": "%d", "name": "%s", "state_id": "%d" }';

my $jd = JSON->new;
my $ua = LWP::UserAgent->new;

my $endpoint = 'http://localhost:8000/city';

open(FH, '<', '/Users/daniel/Projects/GitHub/3012978/estados-cidades2.json');

my $data = do {
	local $/;
	<FH>;
};

close(FH);

my $allData = $jd->decode($data);

foreach (@{ $allData->{cities} }) {
	my $id = $_->{id};
	my $nome = $_->{name} || $_->{nome};
	my $state_id = $_->{state_id};

	print $id, "\t", $nome, "\t", $state_id, "\n";

	my $request = HTTP::Request->new(PUT => $endpoint);
	$request->header('content-type' => 'application/json');
	$request->content(sprintf($s, $id, $nome, $state_id));

	my $response = $ua->request($request);
	if ($response->is_success) {
		my $msg = $response->decoded_content;
		print "Sucesso, resposta : ", $msg, "\n";
	}
	else {
		print "Erro, resposta : ", $response->code, " -> ", $response->message, "\n";
	}

	sleep 1;
}
