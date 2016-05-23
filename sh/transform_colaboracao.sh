#!/bin/bash

#Declarando variáveis

vocabci_result=""
vocabci_result_id=""

#Funções

consulta_termo() {
  query=$(echo $1 | sed 's/ /+/g')
  url="http://bdpife2.sibi.usp.br/instituicoes/vocab/services.php?task=fetch&arg=$query"
  vocabci_result_id=$(curl -s -G -L $url | xmlstarlet sel -t -v "//term_id")
  url2="http://bdpife2.sibi.usp.br/instituicoes/vocab/services.php?task=fetchTerm&arg=$vocabci_result_id"
  vocabci_result=$(curl -s -G -L $url2 | xmlstarlet sel -t -v "//string")
}

count_record=1

IFS=$'\n'       # make newlines the only separator
for line in $(cat $1);
do

  echo $count_record
  count_record=$((count_record+1))

line=$(printf "%s\n" "$line" | sed "s/\"\",\"\"/\|/g" | sed "s/,\"/#/g" | sed 's/\[\"\"//g' | sed 's/\"\"\]\"//g' )
_id=$(printf "%s\n" "$line" | cut -d "#" -f 1 | sed 's/\"//g')
instituicao=$(printf "%s\n" "$line" | cut -d "#" -f 2 )



consulta_termo $instituicao_limpa

IFS='|' read -ra instituicao <<< "$instituicao"
	for i in "${instituicao[@]}"; do
    instituicao_limpa=$(echo $i | sed "s/[^a-z|0-9|A-Z| ]//g" | sed 's/^\s//' | sed 's/  / /g' | sed 's/^ //g' | sed 's/ $//g')

     consulta_termo $instituicao_limpa
     result_count_subject=$(echo "$vocabci_result" | wc -m)

     			#Se autor teve resposta
     			if  [[ $result_count_subject -gt "1" ]]
     			then
     				instituicao_tematres=$(printf "$vocabci_result")
     				instituicao_tematres_id=$(printf "$vocabci_result_id")
            echo "db.producao.update({\"_id\" : \""$_id"\"},{\$addToSet: {colab_instituicao_corrigido: \"$instituicao_tematres\"}})" | mongo sibi
     			else
            echo "db.producao.update({\"_id\" : \""$_id"\"},{\$addToSet: {colab_instituicao_naocorrigido: \"$instituicao_limpa\"}})" | mongo sibi
     			fi

#  line=$(printf "%s\n" "\"$_id\",\"$subject_tematres\",\"$subject_tematres_id\"")
  done

done
