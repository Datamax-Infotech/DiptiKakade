Friend Class DelegateAuthenticationProvider
    Private value As Func(Of Object, Task)

    Public Sub New(value As Func(Of Object, Task))
        Me.value = value
    End Sub
End Class
