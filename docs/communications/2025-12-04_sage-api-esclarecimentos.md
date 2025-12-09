# Comunicações: Integração API SAGE50c

**Data**: 4 de Dezembro de 2025
**Assunto**: Esclarecimentos sobre API Sage50c
**Interlocutor**: Marcelo Zacarias (Profer) - marcelo.zacarias@profer.pt

---

## Email Recebido

**De**: Marcelo Zacarias <marcelo.zacarias@profer.pt>
**Para**: Nelson Brilhante
**Data**: 4 de Dezembro de 2025
**Assunto**: API Sage50c

---

Bom dia,

O Fernando encaminhou a mensagem que enviou, acompanhada do link https://developer.sage.com/accounting/reference/. Não tinha conhecimento prévio desta documentação, até porque, pelo que verifiquei, está orientada sobretudo para o Sage Business Cloud Accounting, que é uma solução distinta do Sage50c.

Relativamente ao API do Sage50c, envio o link do repositório oficial disponibilizado pela Sage para suporte a desenvolvimentos nesta plataforma: https://github.com/sage-portugal/50c-API

Nesse repositório encontram-se vários projetos de exemplo, incluindo dois exemplos de Extensibilidade (funcionalidade que apenas opera como extensão dentro do próprio Sage). Em conjunto com estes projetos estão também presentes duas pastas: "Interops" e "Sage50c.Common".

A pasta "Interops" contém DLLs que permitem que código .NET comunique com as bibliotecas COM expostas pelo Sage.

A pasta "Sage50c.Common" reúne um conjunto de classes utilitárias utilizadas transversalmente pelos vários exemplos.

Envio igualmente os links que me foram disponibilizados anteriormente pela Sage e que incluem documentação e material de apoio adicional:
- https://pt-kb.sage.pt/portal/app/portlets/results/viewsolution.jsp?solutionid=231016080142473
- http://mirror.sage.pt/sage/Sage2018/50c/API/APIProcedimentos.pdf

Os próprios projetos no repositório estão comentados e explicam, de forma razoavelmente clara, o funcionamento das funções e métodos disponíveis.

O projeto "Sage50c.API.Sample" é o mais abrangente e integra a maioria dos métodos relacionados com a criação de documentos (por exemplo, notas de encomenda). Recomendo começar por essa solução e analisar o seu funcionamento. Caso surja alguma questão adicional, dentro do que me for possível esclarecer, estarei disponível para ajudar.

Atentamente,

Os melhores Cumprimentos

---

## Análise Técnica

### Conclusões da Análise do Repositório

Após análise do repositório https://github.com/sage-portugal/50c-API:

| Aspecto | Detalhe |
|---------|---------|
| **Linguagem** | C# (.NET) - 100% do repositório |
| **Tipo de API** | COM Interop (NÃO é REST/HTTP) |
| **Sistema Operativo** | Windows obrigatório |
| **Execução** | Local (mesmo servidor que o Sage50c) |
| **Compilação** | x86 obrigatório |

### Implicações para o Projeto

A arquitetura planeada (Laravel/PHP em VPS Linux) não é compatível diretamente com a API SAGE50c.

**Opções Identificadas:**

1. **Bridge .NET → REST**: Desenvolver microserviço C# no servidor Windows do SAGE que exponha API REST
2. **Aimeos Independente**: E-commerce sem integração SAGE (sincronização manual)
3. **Migração para Sage Cloud**: Versão cloud tem API REST nativa

---

## Email de Resposta (Rascunho)

**Para**: Marcelo Zacarias <marcelo.zacarias@profer.pt>
**Assunto**: Re: API Sage50c - Esclarecimentos Arquiteturais
**Estado**: PENDENTE ENVIO

---

Caro Marcelo,

Muito obrigado pela resposta e pelos esclarecimentos. A informação que partilhou foi extremamente útil para clarificar as diferenças entre as plataformas SAGE.

Após analisar o repositório https://github.com/sage-portugal/50c-API e a documentação associada, verifiquei que a API do Sage50c opera através de COM Interop em ambiente Windows/.NET, e não via REST/HTTP como inicialmente antecipávamos.

Esta característica técnica tem implicações significativas para o nosso projeto, uma vez que a arquitetura planeada assenta num backend Laravel (PHP) a correr em VPS Linux.

Para podermos avançar com a análise de viabilidade, gostaria de esclarecer algumas questões:

1. **Localização do Sage50c**
   Onde está atualmente instalado o Sage50c que pretendemos integrar? (servidor interno Windows, cloud, posto de trabalho local?)

2. **Âmbito da Integração**
   Que operações seriam necessárias através da API?
   - Apenas leitura: consultar produtos, preços, níveis de stock?
   - Escrita: criar encomendas, emitir documentos de faturação?
   - Ambos?

3. **Conectividade de Rede**
   O servidor onde está instalado o Sage50c tem acesso à internet e possibilidade de expor endpoints (ainda que internamente através de VPN ou túnel seguro)?

4. **Desenvolvimento .NET**
   Caso seja necessário desenvolver um serviço intermediário em C#/.NET para expor uma API REST (que seria depois consumida pela nossa aplicação web), a Profer disponibiliza esse tipo de serviço? Ou teremos de recorrer a um terceiro especializado em .NET?

5. **Alternativa Cloud**
   Existe possibilidade ou planos de migração para o Sage Business Cloud Accounting no futuro? Esta versão dispõe de API REST nativa, o que simplificaria substancialmente a integração.

Compreendo que algumas destas questões possam estar fora do seu âmbito direto, mas qualquer orientação será valiosa para definirmos a melhor estratégia de integração.

Com os melhores cumprimentos,
Nelson Brilhante

---

## Próximos Passos

- [ ] Enviar email de resposta ao Marcelo
- [ ] Aguardar esclarecimentos sobre localização e âmbito
- [ ] Decidir estratégia de integração com base nas respostas
- [ ] Atualizar documentação do projeto conforme decisão

---

## Referências

- Repositório SAGE50c API: https://github.com/sage-portugal/50c-API
- Documentação KB SAGE: https://pt-kb.sage.pt/portal/app/portlets/results/viewsolution.jsp?solutionid=231016080142473
- PDF Procedimentos API: http://mirror.sage.pt/sage/Sage2018/50c/API/APIProcedimentos.pdf
