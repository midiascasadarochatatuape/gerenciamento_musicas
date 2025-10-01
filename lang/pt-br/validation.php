<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O :attribute deve ser aceito.',
    'accepted_if' => 'O :attribute deve ser aceito quando :other for :value.',
    'active_url' => 'O :attribute deve ser uma URL válida.',
    'after' => 'O :attribute deve ser uma data após :date.',
    'after_or_equal' => 'O :attribute deve ser uma data após ou igual a :date.',
    'alpha' => 'O :attribute deve conter apenas letras.',
    'alpha_dash' => 'O :attribute deve conter apenas letras, números, hifens e underscores.',
    'alpha_num' => 'O :attribute deve conter apenas letras e números.',
    'any_of' => 'O :attribute é inválido.',
    'array' => 'O :attribute deve ser um array.',
    'ascii' => 'O :attribute deve conter apenas caracteres alfanuméricos e símbolos.',
    'before' => 'O :attribute deve ser uma data antes de :date.',
    'before_or_equal' => 'O :attribute deve ser uma data antes ou igual a :date.',
    'between' => [
        'array' => 'O :attribute deve ter entre :min e :max itens.',
        'file' => 'O :attribute deve ter entre :min e :max kilobytes.',
        'numeric' => 'O :attribute deve ter entre :min e :max.',
        'string' => 'O :attribute deve ter entre :min e :max caracteres.',
    ],
    'boolean' => 'O :attribute deve ser verdadeiro ou falso.',
    'can' => 'O :attribute contém um valor não autorizado.',
    'confirmed' => 'A confirmação do :attribute não corresponde.',
    'contains' => 'O campo :attribute deve conter um valor.',
    'current_password' => 'A senha está incorreta.',
    'date' => 'O :attribute deve ser uma data válida.',
    'date_equals' => 'O :attribute deve ser uma data igual a :date.',
    'date_format' => 'O :attribute deve corresponder ao formato :format.',
    'decimal' => 'O :attribute deve ter :decimal casas decimais.',
    'declined' => 'O :attribute deve ser negado.',
    'declined_if' => 'O :attribute deve ser negado quando :other for :value.',
    'different' => 'O :attribute deve ser diferente de :other.',
    'digits' => 'O :attribute deve ter :digits dígitos.',
    'digits_between' => 'O :attribute deve ter entre :min e :max dígitos.',
    'dimensions' => 'O :attribute possui dimensões de imagem inválidas.',
    'distinct' => 'O :attribute possui um valor duplicado.',
    'doesnt_end_with' => 'O :attribute não deve terminar com um dos seguintes: :values.',
    'doesnt_start_with' => 'O :attribute não deve começar com um dos seguintes: :values.',
    'email' => 'O :attribute deve ser um endereço de e-mail válido.',
    'ends_with' => 'O :attribute deve terminar com um dos seguintes: :values.',
    'enum' => 'O :attribute é inválido.',
    'exists' => 'O :attribute é inválido.',
    'extensions' => 'O :attribute deve ter uma das seguintes extensões: :values.',
    'file' => 'O :attribute deve ser um arquivo.',
    'filled' => 'O :attribute deve ter um valor.',
    'gt' => [
        'array' => 'O :attribute deve ter mais de :value itens.',
        'file' => 'O :attribute deve ser maior que :value kilobytes.',
        'numeric' => 'O :attribute deve ser maior que :value.',
        'string' => 'O :attribute deve ser maior que :value caracteres.',
        'password' => 'A senha deve conter pelo menos 8 caracteres.',
    ],
    'gte' => [
        'array' => 'O :attribute deve ter pelo menos :value itens.',
        'file' => 'O :attribute deve ser maior ou igual a :value kilobytes.',
        'numeric' => 'O :attribute deve ser maior ou igual a :value.',
        'string' => 'O :attribute deve ser maior ou igual a :value caracteres.',
    ],
    'hex_color' => 'O :attribute deve ser uma cor hexadecimal válida.',
    'image' => 'O :attribute deve ser uma imagem.',
    'in' => 'O :attribute é inválido.',
    'in_array' => 'O :attribute field must exist in :other.',
    'integer' => 'O :attribute field must be an integer.',
    'ip' => 'O :attribute field must be a valid IP address.',
    'ipv4' => 'O :attribute field must be a valid IPv4 address.',
    'ipv6' => 'O :attribute field must be a valid IPv6 address.',
    'json' => 'O :attribute field must be a valid JSON string.',
    'list' => 'O :attribute field must be a list.',
    'lowercase' => 'O :attribute field must be lowercase.',
    'lt' => [
        'array' => 'O :attribute deve ter menos de :value itens.',
        'file' => 'O :attribute deve ser menor que :value kilobytes.',
        'numeric' => 'O :attribute deve ser menor que :value.',
        'string' => 'O :attribute deve ser menor que :value caracteres.',
    ],
    'lte' => [
        'array' => 'O :attribute deve ter no máximo :value itens.',
        'file' => 'O :attribute deve ser menor ou igual a :value kilobytes.',
        'numeric' => 'O :attribute deve ser menor ou igual a :value.',
        'string' => 'O :attribute deve ser menor ou igual a :value caracteres.',
    ],
    'mac_address' => 'O :attribute deve ser um endereço MAC válido.',
    'max' => [
        'array' => 'O :attribute deve ter no máximo :max itens.',
        'file' => 'O :attribute deve ter no máximo :max kilobytes.',
        'numeric' => 'O :attribute deve ter no máximo :max.',
        'string' => 'O :attribute deve ter no máximo :max caracteres.',
        'password' => 'A senha deve ter no máximo :max caracteres.',
    ],
    'max_digits' => 'O :attribute deve ter no máximo :max dígitos.',
    'mimes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'array' => 'O :attribute deve ter pelo menos :min itens.',
        'file' => 'O :attribute deve ser pelo menos :min kilobytes.',
        'numeric' => 'O :attribute deve ser pelo menos :min.',
        'string' => 'O :attribute deve ser pelo menos :min caracteres.',
    ],
    'min_digits' => 'O :attribute deve ter pelo menos :min dígitos.',
    'missing' => 'O :attribute deve ser ausente.',
    'missing_if' => 'O :attribute deve ser ausente quando :other for :value.',
    'missing_unless' => 'O :attribute deve ser ausente a menos que :other for :value.',
    'missing_with' => 'O :attribute deve ser ausente quando :values estiver presente.',
    'missing_with_all' => 'O :attribute deve ser ausente quando :values estiverem presentes.',
    'multiple_of' => 'O :attribute deve ser um múltiplo de :value.',
    'not_in' => 'O :attribute é inválido.',
    'not_regex' => 'O :attribute field format is invalid.',
    'numeric' => 'O :attribute field must be a number.',
    'password' => [
        'letters' => 'O :attribute deve conter pelo menos uma letra.',
        'mixed' => 'O :attribute deve conter pelo menos uma letra maiúscula e uma minúscula.',
        'numbers' => 'O :attribute deve conter pelo menos um número.',
        'symbols' => 'O :attribute deve conter pelo menos um símbolo.',
        'uncompromised' => 'A senha fornecida tem aparecido em um vazamento de dados. Por favor, escolha uma senha diferente.',
        'password' => 'A senha deve conter pelo menos 8 caracteres.',
        'confirm' => 'A confirmação da senha é inválida.',
        'password_confirm' => 'A confirmação da senha é inválida.',
    ],
    'present' => 'O :attribute deve estar presente.',
    'present_if' => 'O :attribute deve estar presente quando :other for :value.',
    'present_unless' => 'O :attribute deve estar presente a menos que :other for :value.',
    'present_with' => 'O :attribute deve estar presente quando :values estiver presente.',
    'present_with_all' => 'O :attribute deve estar presente quando :values estiverem presentes.',
    'prohibited' => 'O :attribute deve ser proibido.',
    'prohibited_if' => 'O :attribute deve ser proibido quando :other for :value.',
    'prohibited_if_accepted' => 'O :attribute deve ser proibido quando :other for aceito.',
    'prohibited_if_declined' => 'O :attribute deve ser proibido quando :other for rejeitado.',
    'prohibited_unless' => 'O :attribute deve ser proibido a menos que :other for :values.',
    'prohibits' => 'O :attribute deve proibir :other de estar presente.',
    'regex' => 'O :attribute field format is invalid.',
    'required' => 'O :attribute field is required.',
    'required_array_keys' => 'O :attribute field must contain entries for: :values.',
    'required_if' => 'O :attribute deve estar presente quando :other for :value.',
    'required_if_accepted' => 'O :attribute deve estar presente quando :other for aceito.',
    'required_if_declined' => 'O :attribute deve estar presente quando :other for rejeitado.',
    'required_unless' => 'O :attribute deve estar presente a menos que :other for :values.',
    'required_with' => 'O :attribute deve estar presente quando :values estiver presente.',
    'required_with_all' => 'O :attribute deve estar presente quando :values estiverem presentes.',
    'required_without' => 'O :attribute deve estar presente quando :values não estiver presente.',
    'required_without_all' => 'O :attribute deve estar presente quando nenhum de :values estiver presente.',
    'same' => 'O :attribute deve corresponder a :other.',
    'size' => [
        'array' => 'O :attribute deve conter :size itens.',
        'file' => 'O :attribute deve ser :size kilobytes.',
        'numeric' => 'O :attribute deve ser :size.',
        'string' => 'O :attribute deve ser :size caracteres.',
    ],
    'starts_with' => 'O :attribute deve começar com um dos seguintes: :values.',
    'string' => 'O :attribute deve ser uma string.',
    'timezone' => 'O :attribute deve ser um fuso horário válido.',
    'unique' => 'O :attribute já foi taken.',
    'uploaded' => 'O :attribute failed to upload.',
    'uppercase' => 'O :attribute deve ser maiúsculo.',
    'url' => 'O campo :attribute deve ser uma URL válida.',
    'ulid' => 'O campo :attribute deve ser uma ULID válida.',
    'uuid' => 'O campo :attribute deve ser uma UUID válida.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
