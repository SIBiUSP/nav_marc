db.producao.find( {year: { $exists: false }} )

db.producao.update({ year: { $exists: false }}, { $set: { "year": "Sem data registrada" }}, false, true )

db.producao.update({ internacionalizacao: { $exists: false }}, { $set: { "internacionalizacao": "Sem dados de internacionalização" }}, false, true )

db.producao.update({ unidadeUSPtrabalhos: { $exists: false }}, { $addToSet: { "unidadeUSPtrabalhos": "Sem Unidade registrada" }}, false, true )

db.producao.update({ subject: { $exists: false }}, { $set: { "subject": "Sem assunto cadastrado" }}, false, true )
