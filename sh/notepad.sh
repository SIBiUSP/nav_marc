#create index
db.producao.createIndex({title:"text",authors:"text",subject:"text",evento:"text",ispartof:"text"},{language_override:"pt",weights:{title: 10,autor: 9,subject:9,evento:2,ispartof:2},name:"TextIndex"})
#fields index
db.producao.createIndex( { type: 1 } )
db.producao.createIndex( { unidadeUSPtrabalhos: 1 } )
db.producao.createIndex( { unidadeUSP: 1 } )
db.producao.createIndex( { departamento: 1 } )
db.producao.createIndex( { departamentotrabalhos: 1 } )
db.producao.createIndex( { subject: 1 } )
db.producao.createIndex( { authors: 1 } )
db.producao.createIndex( { colab: 1 } )
db.producao.createIndex( { colab_int: 1 } )
db.producao.createIndex( { colab_int_trab: 1 } )
db.producao.createIndex( { colab_instituicao: 1 } )
db.producao.createIndex( { colab_instituicao_trab: 1 } )
db.producao.createIndex( { colab_instituicao_corrigido: 1 } )
db.producao.createIndex( { colab_instituicao_naocorrigido: 1 } )
db.producao.createIndex( { dataregistro: 1 } )
db.producao.createIndex( { areaconcentracao: 1 } )
db.producao.createIndex( { fatorimpacto: 1 } )
db.producao.createIndex( { grupopesquisa: 1 } )
db.producao.createIndex( { authorUSP: 1 } )
db.producao.createIndex( { codpesbusca: 1 } )
db.producao.createIndex( { codpes: 1 } )
db.producao.createIndex( { ispartof: 1 } )
db.producao.createIndex( { issn_part: 1 } )
db.producao.createIndex( { indexado: 1 } )
db.producao.createIndex( { evento: 1 } )
db.producao.createIndex( { year: 1 } )
db.producao.createIndex( { language: 1 } )
db.producao.createIndex( { internacionalizacao: 1 } )
db.producao.createIndex( { country: 1 } )


#mongoexport --db sibi --collection producao --type=csv --fields _id,colab_instituicao_trab --query '{ year: { $gte: "2010", $lte: "2015" } }' --out ../data/colab_instituicao.csv

#mongoexport --db sibi --collection producao --type=csv --fields _id,colab_instituicao_corrigido --query '{ colab_instituicao_corrigido: { $exists: true } }' --out ../data/colab_instituicao_corrigido.csv


mongoexport --db sibi --collection producao --fields _id,colab_instituicao_trab --query '{ colab_instituicao_trab: { $exists: true } }' --out ../data/colab_instituicao.json


mongoexport --db sibi --collection producao --fields _id,colab_instituicao_corrigido --query '{ colab_instituicao_corrigido: { $exists: true } }' --out ../data/colab_instituicao_corrigido.json

mongoexport --db sibi --collection producao --fields _id,colab_instituicao_naocorrigido --query '{ colab_instituicao_naocorrigido: { $exists: true } }' --out ../data/colab_instituicao_naocorrigido.json
