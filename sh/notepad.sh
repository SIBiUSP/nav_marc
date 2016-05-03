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
