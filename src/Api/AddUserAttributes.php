<?php

namespace Gtdxyz\Signature\Api;

use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;
use Gtdxyz\Signature\Formatter\SignatureFormatter;

class AddUserAttributes
{
    protected $formatter;
    
    public function __construct(SignatureFormatter $formatter)
    {
        $this->formatter = $formatter;
    }
    
    public function __invoke(UserSerializer $serializer, User $user, array $attributes): array
    {
        $attributes['signature'] = $user->signature ? $this->formatter->unparse($user->signature) : null;
        if ($user->signature) {
            $attributes['signatureHtml'] = $this->formatter->render($user->signature);
        }

        $actor = $serializer->getActor();

        $attributes['canEditSignature'] = $actor->can('editSignature', $user);
        $attributes['canHaveSignature'] = $user->hasPermission('haveSignature');

        return $attributes;
    }
}
