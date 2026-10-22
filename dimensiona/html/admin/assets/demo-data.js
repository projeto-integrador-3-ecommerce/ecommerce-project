/* Dados de demonstração da versão HTML (cópia de /data/*.json). */
window.ADMIN = { api: null, csrf: null, root: '../', data: {
  "loja": {
    "nome": "Dimensiona",
    "cnpj": "00.000.000/0000-00",
    "email": "contato@dimensiona.com.br",
    "telefone": "(11) 9 9999-9999",
    "frete_gratis_min": 299,
    "frete_padrao": 24.9,
    "prazo_entrega": 5,
    "cep_origem": "01310-100",
    "cupom": {
      "codigo": "DIMENSIONA10",
      "desconto": 10,
      "minimo": 0
    },
    "manutencao": false
  },
  "produtos": [
    {
      "id": 1,
      "name": "Lorem ipsum dolor",
      "category": "decoracao",
      "price": 89.0,
      "old_price": null,
      "badge": "novo",
      "img": "assets/img/placeholder.svg",
      "rating": 5.0,
      "reviews": 12,
      "launch": "Set 2026",
      "desc": "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum eveniet dolorum.",
      "status": "ativo"
    },
    {
      "id": 2,
      "name": "Sit amet consectetur",
      "category": "pecas-tecnicas",
      "price": 149.0,
      "old_price": 179.0,
      "badge": "oferta",
      "img": "assets/img/placeholder.svg",
      "rating": 4.8,
      "reviews": 31,
      "launch": "Ago 2026",
      "desc": "Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus nemo repellendus officiis.",
      "status": "ativo"
    },
    {
      "id": 3,
      "name": "Adipisicing elit",
      "category": "miniaturas",
      "price": 59.0,
      "old_price": null,
      "badge": null,
      "img": "assets/img/placeholder.svg",
      "rating": 4.9,
      "reviews": 18,
      "launch": "Ago 2026",
      "desc": "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora recusandae accusantium quae.",
      "status": "ativo"
    },
    {
      "id": 4,
      "name": "Quisquam voluptatem",
      "category": "prototipos",
      "price": 119.0,
      "old_price": null,
      "badge": "novo",
      "img": "assets/img/placeholder.svg",
      "rating": 4.7,
      "reviews": 9,
      "launch": "Set 2026",
      "desc": "Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt mollitia odit facilis.",
      "status": "ativo"
    }
  ],
  "categorias": [
    {
      "slug": "decoracao",
      "name": "Lorem ipsum",
      "desc": "Lorem ipsum dolor sit amet consectetur."
    },
    {
      "slug": "pecas-tecnicas",
      "name": "Dolor sit",
      "desc": "Adipisicing elit quisquam voluptatem nobis."
    },
    {
      "slug": "miniaturas",
      "name": "Amet consectetur",
      "desc": "Eveniet dolorum facilis doloribus nemo."
    },
    {
      "slug": "organizadores",
      "name": "Adipisicing",
      "desc": "Repellendus officiis tempora recusandae."
    },
    {
      "slug": "prototipos",
      "name": "Elit quisquam",
      "desc": "Accusantium quae nesciunt mollitia odit."
    }
  ],
  "pedidos": [
    {
      "codigo": "DIM-2026-4837",
      "cliente": "Carlos Mendes",
      "email": "carlos@email.com",
      "data": "2026-09-29",
      "itens": [
        {
          "produto_id": 2,
          "qtd": 1
        },
        {
          "produto_id": 1,
          "qtd": 1
        }
      ],
      "total": 238.0,
      "status": "confirmado"
    },
    {
      "codigo": "DIM-2026-4833",
      "cliente": "Ana Paula S.",
      "email": "ana@email.com",
      "data": "2026-09-29",
      "itens": [
        {
          "produto_id": 2,
          "qtd": 1
        }
      ],
      "total": 149.0,
      "status": "imprimindo"
    },
    {
      "codigo": "DIM-2026-4829",
      "cliente": "João Costa",
      "email": "joao@email.com",
      "data": "2026-09-28",
      "itens": [
        {
          "produto_id": 3,
          "qtd": 1
        }
      ],
      "total": 59.0,
      "status": "enviado"
    },
    {
      "codigo": "DIM-2026-4826",
      "cliente": "Mariana Alves",
      "email": "mariana@email.com",
      "data": "2026-09-28",
      "itens": [
        {
          "produto_id": 4,
          "qtd": 1
        },
        {
          "produto_id": 1,
          "qtd": 1
        }
      ],
      "total": 208.0,
      "status": "imprimindo"
    },
    {
      "codigo": "DIM-2026-4824",
      "cliente": "Rafael Torres",
      "email": "rafael@email.com",
      "data": "2026-09-27",
      "itens": [
        {
          "produto_id": 1,
          "qtd": 1
        }
      ],
      "total": 89.0,
      "status": "entregue"
    },
    {
      "codigo": "DIM-2026-4821",
      "cliente": "Fernanda Lima",
      "email": "fernanda@email.com",
      "data": "2026-09-27",
      "itens": [
        {
          "produto_id": 2,
          "qtd": 2
        }
      ],
      "total": 298.0,
      "status": "enviado"
    },
    {
      "codigo": "DIM-2026-4818",
      "cliente": "Lucas Pereira",
      "email": "lucas@email.com",
      "data": "2026-09-26",
      "itens": [
        {
          "produto_id": 3,
          "qtd": 2
        },
        {
          "produto_id": 4,
          "qtd": 1
        }
      ],
      "total": 237.0,
      "status": "cancelado"
    },
    {
      "codigo": "DIM-2026-4812",
      "cliente": "Beatriz Rocha",
      "email": "beatriz@email.com",
      "data": "2026-09-25",
      "itens": [
        {
          "produto_id": 1,
          "qtd": 2
        }
      ],
      "total": 178.0,
      "status": "entregue"
    },
    {
      "codigo": "DIM-2026-4805",
      "cliente": "Pedro Nunes",
      "email": "pedro@email.com",
      "data": "2026-09-24",
      "itens": [
        {
          "produto_id": 4,
          "qtd": 1
        }
      ],
      "total": 119.0,
      "status": "entregue"
    },
    {
      "codigo": "DIM-2026-3517",
      "cliente": "Juliana Martins",
      "email": "juliana@email.com",
      "data": "2026-09-20",
      "itens": [
        {
          "produto_id": 2,
          "qtd": 1
        },
        {
          "produto_id": 3,
          "qtd": 1
        }
      ],
      "total": 208.0,
      "status": "entregue"
    }
  ],
  "impressoras": [
    {
      "id": "FDM-01",
      "tipo": "FDM",
      "modelo": "Prusa MK4",
      "status": "imprimindo",
      "job": "Lorem ipsum dolor",
      "progresso": 72,
      "restante": "1h 20min",
      "bico": 210,
      "mesa": 60,
      "material": "PLA Branco"
    },
    {
      "id": "FDM-02",
      "tipo": "FDM",
      "modelo": "Prusa MK4",
      "status": "imprimindo",
      "job": "Sit amet consectetur",
      "progresso": 38,
      "restante": "3h 05min",
      "bico": 240,
      "mesa": 80,
      "material": "PETG Preto"
    },
    {
      "id": "FDM-03",
      "tipo": "FDM",
      "modelo": "Bambu X1",
      "status": "ociosa",
      "job": null,
      "progresso": 0,
      "restante": null,
      "bico": 25,
      "mesa": 24,
      "material": "ABS Cinza"
    },
    {
      "id": "SLA-01",
      "tipo": "SLA",
      "modelo": "Formlabs Form 3",
      "status": "imprimindo",
      "job": "Adipisicing elit",
      "progresso": 91,
      "restante": "25min",
      "bico": null,
      "mesa": 30,
      "material": "Resina Cinza"
    },
    {
      "id": "SLA-02",
      "tipo": "SLA",
      "modelo": "Elegoo Saturn",
      "status": "manutencao",
      "job": null,
      "progresso": 0,
      "restante": null,
      "bico": null,
      "mesa": null,
      "material": "—"
    }
  ],
  "materiais": [
    {
      "id": 1,
      "nome": "PLA Branco 1kg",
      "tipo": "FDM",
      "kg": 0.6,
      "capacidade": 10,
      "minimo": 2.0,
      "preco_kg": 89
    },
    {
      "id": 2,
      "nome": "PLA Preto 1kg",
      "tipo": "FDM",
      "kg": 4.2,
      "capacidade": 10,
      "minimo": 2.0,
      "preco_kg": 89
    },
    {
      "id": 3,
      "nome": "ABS Preto 1kg",
      "tipo": "FDM",
      "kg": 1.1,
      "capacidade": 8,
      "minimo": 1.6,
      "preco_kg": 95
    },
    {
      "id": 4,
      "nome": "PETG Transparente",
      "tipo": "FDM",
      "kg": 5.5,
      "capacidade": 8,
      "minimo": 1.6,
      "preco_kg": 110
    },
    {
      "id": 5,
      "nome": "TPU Flexível",
      "tipo": "FDM",
      "kg": 3.0,
      "capacidade": 5,
      "minimo": 1.0,
      "preco_kg": 150
    },
    {
      "id": 6,
      "nome": "Resina Cinza 500ml",
      "tipo": "SLA",
      "kg": 0.8,
      "capacidade": 6,
      "minimo": 1.2,
      "preco_kg": 180
    },
    {
      "id": 7,
      "nome": "Resina Transparente",
      "tipo": "SLA",
      "kg": 3.5,
      "capacidade": 6,
      "minimo": 1.2,
      "preco_kg": 190
    },
    {
      "id": 8,
      "nome": "Resina Preta 1L",
      "tipo": "SLA",
      "kg": 4.0,
      "capacidade": 6,
      "minimo": 1.2,
      "preco_kg": 170
    }
  ],
  "painel": {
    "atualizado": "29 set 2026, 14:32",
    "kpis": {
      "receita": 31900,
      "receita_var": 8.2,
      "pedidos_mes": 247,
      "pedidos_var": 12.5,
      "ticket": 129.15,
      "ticket_var": -3.8,
      "cancelamento": 4.2,
      "cancelamento_var": -0.6,
      "pedidos_compra": 2
    },
    "meses": [
      "Abr",
      "Mai",
      "Jun",
      "Jul",
      "Ago",
      "Set"
    ],
    "receita": [
      18400,
      21200,
      19800,
      24600,
      28100,
      31900
    ],
    "meta": [
      20000,
      21000,
      22000,
      24000,
      26000,
      30000
    ],
    "pedidos_mensais": [
      142,
      168,
      155,
      190,
      221,
      247
    ],
    "vendas_categoria": {
      "decoracao": 34,
      "pecas-tecnicas": 26,
      "miniaturas": 18,
      "organizadores": 12,
      "prototipos": 10
    },
    "dias": [
      "Seg",
      "Ter",
      "Qua",
      "Qui",
      "Sex",
      "Sáb",
      "Dom"
    ],
    "pedidos_semana": {
      "Recebidos": [
        12,
        15,
        11,
        18,
        22,
        14,
        8
      ],
      "Enviados": [
        10,
        13,
        12,
        15,
        19,
        12,
        6
      ],
      "Cancelados": [
        1,
        0,
        2,
        1,
        0,
        1,
        0
      ]
    },
    "horas_impressao": [
      42,
      51,
      44,
      58,
      53,
      31,
      19
    ],
    "consumo": {
      "PLA": [
        18,
        20,
        17,
        22,
        24,
        27
      ],
      "PETG": [
        6,
        7,
        8,
        7,
        9,
        11
      ],
      "Resina": [
        3,
        4,
        4,
        5,
        6,
        7
      ]
    },
    "top_produtos": [
      {
        "id": 2,
        "vendas": 84
      },
      {
        "id": 1,
        "vendas": 71
      },
      {
        "id": 4,
        "vendas": 52
      },
      {
        "id": 3,
        "vendas": 40
      }
    ],
    "clientes": {
      "total": 1847,
      "total_var": 6.1,
      "novos": 143,
      "novos_var": 9.4,
      "recorrencia": 64,
      "recorrencia_var": 2.3,
      "nps": 72,
      "nps_var": 3,
      "crescimento_total": [
        1290,
        1395,
        1480,
        1590,
        1704,
        1847
      ],
      "crescimento_novos": [
        88,
        105,
        85,
        110,
        114,
        143
      ],
      "retencao": [
        58,
        61,
        57,
        63,
        62,
        64
      ],
      "avaliacoes": [
        412,
        138,
        31,
        9,
        4
      ],
      "melhores": [
        {
          "nome": "Carlos Mendes",
          "pedidos": 24,
          "total": 3894,
          "vip": true
        },
        {
          "nome": "Ana Paula S.",
          "pedidos": 19,
          "total": 3180,
          "vip": true
        },
        {
          "nome": "João Costa",
          "pedidos": 17,
          "total": 2616,
          "vip": true
        },
        {
          "nome": "Rafael Torres",
          "pedidos": 12,
          "total": 1954,
          "vip": false
        },
        {
          "nome": "Mariana Alves",
          "pedidos": 10,
          "total": 1449,
          "vip": false
        },
        {
          "nome": "Fernanda Lima",
          "pedidos": 8,
          "total": 1033,
          "vip": false
        }
      ]
    }
  }
} };
