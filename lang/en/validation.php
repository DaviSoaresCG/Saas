<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Linhas de idioma de validação
    |--------------------------------------------------------------------------
    |
    | As linhas de idioma a seguir contêm as mensagens de erro padrão usadas
    | pela classe de validador. Algumas dessas regras têm múltiplas versões,
    | como as regras de tamanho. Sinta-se à vontade para ajustar cada uma.
    |
    */

    'accepted' => 'Deve ser aceito.',
    'accepted_if' => 'Deve ser aceito quando :other for :value.',
    'active_url' => 'Deve ser uma URL válida.',
    'after' => 'Deve ser uma data posterior a :date.',
    'after_or_equal' => 'Deve ser uma data posterior ou igual a :date.',
    'alpha' => 'Deve conter apenas letras.',
    'alpha_dash' => 'Deve conter apenas letras, números, traços e underscores.',
    'alpha_num' => 'Deve conter apenas letras e números.',
    'any_of' => 'É inválido.',
    'array' => 'Deve ser um array.',
    'ascii' => 'Deve conter apenas caracteres alfanuméricos e símbolos de um byte.',
    'before' => 'Deve ser uma data anterior a :date.',
    'before_or_equal' => 'Deve ser uma data anterior ou igual a :date.',
    'between' => [
        'array' => 'Deve ter entre :min e :max itens.',
        'file' => 'Deve ter entre :min e :max kilobytes.',
        'numeric' => 'Deve estar entre :min e :max.',
        'string' => 'Deve ter entre :min e :max caracteres.',
    ],
    'boolean' => 'Deve ser verdadeiro ou falso.',
    'can' => 'Contém um valor não autorizado.',
    'confirmed' => 'A confirmação não confere.',
    'contains' => 'Está faltando um valor obrigatório.',
    'current_password' => 'A senha está incorreta.',
    'date' => 'Deve ser uma data válida.',
    'date_equals' => 'Deve ser uma data igual a :date.',
    'date_format' => 'Deve corresponder ao formato :format.',
    'decimal' => 'Deve ter :decimal casas decimais.',
    'declined' => 'Deve ser recusado.',
    'declined_if' => 'Deve ser recusado quando :other for :value.',
    'different' => 'Deve ser diferente de :other.',
    'digits' => 'Deve ter :digits dígitos.',
    'digits_between' => 'Deve ter entre :min e :max dígitos.',
    'dimensions' => 'Possui dimensões de imagem inválidas.',
    'distinct' => 'Possui um valor duplicado.',
    'doesnt_end_with' => 'Não deve terminar com um dos seguintes: :values.',
    'doesnt_start_with' => 'Não deve começar com um dos seguintes: :values.',
    'email' => 'Deve ser um endereço de e-mail válido.',
    'ends_with' => 'Deve terminar com um dos seguintes: :values.',
    'enum' => 'O valor selecionado é inválido.',
    'exists' => 'O valor selecionado é inválido.',
    'extensions' => 'Deve ter uma das seguintes extensões: :values.',
    'file' => 'Deve ser um arquivo.',
    'filled' => 'Deve ter um valor.',
    'gt' => [
        'array' => 'Deve ter mais de :value itens.',
        'file' => 'Deve ser maior que :value kilobytes.',
        'numeric' => 'Deve ser maior que :value.',
        'string' => 'Deve ter mais que :value caracteres.',
    ],
    'gte' => [
        'array' => 'Deve ter :value itens ou mais.',
        'file' => 'Deve ser maior ou igual a :value kilobytes.',
        'numeric' => 'Deve ser maior ou igual a :value.',
        'string' => 'Deve ter :value caracteres ou mais.',
    ],
    'hex_color' => 'Deve ser uma cor hexadecimal válida.',
    'image' => 'Deve ser uma imagem.',
    'in' => 'O valor selecionado é inválido.',
    'in_array' => 'Deve existir em :other.',
    'in_array_keys' => 'Deve conter pelo menos uma das seguintes chaves: :values.',
    'integer' => 'Deve ser um número inteiro.',
    'ip' => 'Deve ser um endereço IP válido.',
    'ipv4' => 'Deve ser um endereço IPv4 válido.',
    'ipv6' => 'Deve ser um endereço IPv6 válido.',
    'json' => 'Deve ser uma string JSON válida.',
    'list' => 'Deve ser uma lista.',
    'lowercase' => 'Deve estar em letras minúsculas.',
    'lt' => [
        'array' => 'Deve ter menos de :value itens.',
        'file' => 'Deve ser menor que :value kilobytes.',
        'numeric' => 'Deve ser menor que :value.',
        'string' => 'Deve ter menos que :value caracteres.',
    ],
    'lte' => [
        'array' => 'Não deve ter mais que :value itens.',
        'file' => 'Deve ser menor ou igual a :value kilobytes.',
        'numeric' => 'Deve ser menor ou igual a :value.',
        'string' => 'Deve ter no máximo :value caracteres.',
    ],
    'mac_address' => 'Deve ser um endereço MAC válido.',
    'max' => [
        'array' => 'Não deve ter mais que :max itens.',
        'file' => 'Não deve ser maior que :max kilobytes.',
        'numeric' => 'Não deve ser maior que :max.',
        'string' => 'Deve ter no máximo :max caracteres.',
    ],
    'max_digits' => 'Não deve ter mais que :max dígitos.',
    'mimes' => 'Deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'Deve ser um arquivo do tipo: :values.',
    'min' => [
        'array' => 'Deve ter pelo menos :min itens.',
        'file' => 'Deve ter pelo menos :min kilobytes.',
        'numeric' => 'Deve ser pelo menos :min.',
        'string' => 'Deve ter no mínimo :min caracteres.',
    ],
    'min_digits' => 'Deve ter pelo menos :min dígitos.',
    'missing' => 'Deve estar ausente.',
    'missing_if' => 'Deve estar ausente quando :other for :value.',
    'missing_unless' => 'Deve estar ausente, a menos que :other seja :value.',
    'missing_with' => 'Deve estar ausente quando :values estiver presente.',
    'missing_with_all' => 'Deve estar ausente quando :values estiverem presentes.',
    'multiple_of' => 'Deve ser múltiplo de :value.',
    'not_in' => 'O valor selecionado é inválido.',
    'not_regex' => 'O formato é inválido.',
    'numeric' => 'Deve ser um número.',
    'password' => [
        'letters' => 'Deve conter pelo menos uma letra.',
        'mixed' => 'Deve conter pelo menos uma letra maiúscula e uma minúscula.',
        'numbers' => 'Deve conter pelo menos um número.',
        'symbols' => 'Deve conter pelo menos um símbolo.',
        'uncompromised' => 'O valor informado apareceu em um vazamento de dados. Por favor escolha outro valor.',
    ],
    'present' => 'Deve estar presente.',
    'present_if' => 'Deve estar presente quando :other for :value.',
    'present_unless' => 'Deve estar presente, a menos que :other seja :value.',
    'present_with' => 'Deve estar presente quando :values estiver presente.',
    'present_with_all' => 'Deve estar presente quando :values estiverem presentes.',
    'prohibited' => 'É proibido.',
    'prohibited_if' => 'É proibido quando :other for :value.',
    'prohibited_if_accepted' => 'É proibido quando :other for aceito.',
    'prohibited_if_declined' => 'É proibido quando :other for recusado.',
    'prohibited_unless' => 'É proibido, a menos que :other esteja em :values.',
    'prohibits' => 'Proíbe :other de estar presente.',
    'regex' => 'O formato é inválido.',
    'required' => 'É obrigatório.',
    'required_array_keys' => 'Deve conter entradas para: :values.',
    'required_if' => 'É obrigatório quando :other for :value.',
    'required_if_accepted' => 'É obrigatório quando :other for aceito.',
    'required_if_declined' => 'É obrigatório quando :other for recusado.',
    'required_unless' => 'É obrigatório, a menos que :other esteja em :values.',
    'required_with' => 'É obrigatório quando :values estiver presente.',
    'required_with_all' => 'É obrigatório quando :values estiverem presentes.',
    'required_without' => 'É obrigatório quando :values não estiver presente.',
    'required_without_all' => 'É obrigatório quando nenhum dos :values estiver presente.',
    'same' => 'Devem corresponder a :other.',
    'size' => [
        'array' => 'Deve conter :size itens.',
        'file' => 'Deve ter :size kilobytes.',
        'numeric' => 'Deve ser :size.',
        'string' => 'Deve ter :size caracteres.',
    ],
    'starts_with' => 'Deve começar com um dos seguintes: :values.',
    'string' => 'Deve ser uma string.',
    'timezone' => 'Deve ser um fuso horário válido.',
    'unique' => 'Já foi utilizado.',
    'uploaded' => 'O upload falhou.',
    'uppercase' => 'Deve estar em letras maiúsculas.',
    'url' => 'Deve ser uma URL válida.',
    'ulid' => 'Deve ser um ULID válido.',
    'uuid' => 'Deve ser um UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Mensagens de validação customizadas
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar mensagens de validação personalizadas para
    | atributos usando a convenção "attribute.rule" para nomear as linhas.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'mensagem personalizada',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atributos de validação customizados
    |--------------------------------------------------------------------------
    |
    | As linhas a seguir são usadas para substituir o placeholder do atributo
    | por algo mais legível, como "Endereço de E-Mail" em vez de "email".
    |
    */

    'attributes' => [],

];
