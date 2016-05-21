#!/bin/bash

rm ../data/colab_instituicao.csv
sleep 2
#mongoexport --db sibi --collection producao --type=csv --fields _id,colab_instituicao_trab --query '{ year: { $gte: "2010", $lte: "2015" }, colab_instituicao_trab: { $exists: true } }' --out ../data/colab_instituicao.csv
mongoexport --db sibi --collection producao --type=csv --fields _id,colab_instituicao_trab --query '{ colab_instituicao_trab: { $exists: true } }' --out ../data/colab_instituicao.csv
sleep 2
echo 'db.producao.update({},{$unset: {colab_instituicao_corrigido:1}},false,true)' | mongo sibi
echo 'db.producao.update({},{$unset: {colab_instituicao_naocorrigido:1}},false,true)' | mongo sibi
sleep 2
./transform_colaboracao.sh ../data/colab_instituicao.csv
